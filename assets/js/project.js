// global CONSTANTS
const TIMEOUT_WAIT = 350;

const waitForElement = function(selector) {
    return new Promise(resolve => {
        const waitTimeout = setTimeout(() => {
            if (document.querySelector(selector)) {
                clearTimeout(waitTimeout);
                return resolve(document.querySelectorAll(selector));
            }
        }, TIMEOUT_WAIT);
    });
};

const contrastingColor = function(color) {
    return (luma(color) >= 165) ? '000' : 'fff';
}

function luma(color) {
    var rgb = (typeof color === 'string') ? hexToRGBArray(color) : color;
    return (0.2126 * rgb[0]) + (0.7152 * rgb[1]) + (0.0722 * rgb[2]);
}

function hexToRGBArray(color) {
    if (color.length === 3)
        color = color.charAt(0) + color.charAt(0) + color.charAt(1) + color.charAt(1) + color.charAt(2) + color.charAt(2);
    else if (color.length !== 6)
        throw('Invalid hex color: ' + color);
    var rgb = [];
    for (var i = 0; i <= 2; i++)
        rgb[i] = parseInt(color.substr(i * 2, 2), 16);
    return rgb;
}

const rgba2hex = (rgba) => `#${rgba.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+\.{0,1}\d*))?\)$/).slice(1).map((n, i) => (i === 3 ? Math.round(parseFloat(n) * 255) : parseFloat(n)).toString(16).padStart(2, '0').replace('NaN', '')).join('')}`

const getProjectBasicsThis = async function() {
    const projectInner = await waitForElement(".projectcolor-container-inner");
    return $(projectInner);
}

const setSaveValues = async function($repeaterItemInner = null, context = null) {
    let $this;
    if ($repeaterItemInner) {
        $this = $repeaterItemInner;
    } else if (context === 'editItem') {
        $this = await getProjectBasicsThis();
    } else {
        $this = $(".projectcolor-container-inner");
    }
    if ($this && $this instanceof jQuery) {
        $this.each(function(index) {

            // set right name for checkbox, label and hidden input to save it right
            const $checkbox = $(this).find(".project-color-checkbox");
            const $thisRepeaterItem = $(this).closest(".field-repeater-item");

            const repeaterIndex = $thisRepeaterItem.index();
            const checkboxName = $checkbox.prop("name");

            const checkboxNameNumbers = checkboxName.match(/[[(0-9).]+?]/g);

            let checkCheckboxName = "";
            checkCheckboxName = 'Project[basics]' + checkboxNameNumbers[0] + '[_project_color]' + '[' + repeaterIndex + ']' + checkboxNameNumbers[2];

            if (checkCheckboxName) {
                const $label = $(this).find(".storm-icon-pseudo");
                $checkbox.prop("name", checkCheckboxName).prop("id", checkCheckboxName);
                $label.prop("for", checkCheckboxName).prop("id", checkCheckboxName);
                if ($checkbox.is(":checked")) {
                    $label.addClass("project-color--checked");
                }
            }

        });
    }
}

const setValues = async function($repeaterItemInner = null, context) {
    let $this;
    if ($repeaterItemInner) {
        $this = $repeaterItemInner;
    } else if (context === "PerformanceObserver") {
        $this = await getProjectBasicsThis();
    } else {
        $this = $(".projectcolor-container-inner");
    }
    if ($this && $this instanceof jQuery) {
        $this.each(function() {
            // set values
            const update = $(".project-color-update").val();
            if (update === "true") {
                const $checkbox = $(this).find(".project-color-checkbox");
                const allValues = $(".project-color-values").val();
                let allValuesEncoded;
                try {
                    allValuesEncoded = $.parseJSON(allValues);
                } catch(error) {
                    console.warn("Couldn't parse values for setting checkboxes values!");
                }
                const $thisRepeaterItem = $(this).closest(".field-repeater-item");
                const repeaterIndex = $thisRepeaterItem.index();
                if (allValuesEncoded) {
                    Object.entries(allValuesEncoded).map((colorValues, index) => {
                        if (index === repeaterIndex) {
                            const $closestInnerContainer = $(this).closest(".projectcolor-container-inner");
                            const checkedValue = $closestInnerContainer.prop("id").replace("projectcolor-container_value-", "");
                            Object.entries(colorValues[1]).map((colorValue, index) => {
                                if (colorValue[1]?.value !== null && checkedValue === colorValue[1]?.value) {
                                    $checkbox.val(checkedValue);
                                    $checkbox.attr("checked", true);
                                    $checkbox.next("label").addClass("project-color--checked");
                                }
                            });
                        }
                    });
                }
            }
        });
    }
}

const setColors = async function($repeaterItemInner = null, context) {
    let $this;
    if ($repeaterItemInner) {
        $this = $repeaterItemInner;
    } else if (context === "PerformanceObserver") {
        $this = await getProjectBasicsThis();
    } else {
        $this = $(".projectcolor-container-inner");
    }
    if ($this && $this instanceof jQuery) {
        $this.each(function() {
            // set font color
            const $colorValueElement = $(this).find(".color-value");
            let colorValue = $colorValueElement.css("background-color");
            colorValue = rgba2hex(colorValue).substring(1);
            const contrastingColorValue = contrastingColor(colorValue);
            $colorValueElement.css("color", "#" + contrastingColorValue);
        });
    }
}

// on navigate (page rendered)
addEventListener('page:render', function(event, context, data, status) {
    if (
        event.target?.location?.pathname.includes('/backend/maki3000/project/projects/create') ||
        event.target?.location?.pathname.includes('/backend/maki3000/project/projects/update')
    ) {
        setSaveValues(null);
        setValues(null);
        setColors(null);
    }
});

$(function() {

    // set new repeater field ajax success listener
    $(document).on('ajaxSuccess', function(event, context, data, status) {
        if (context.handler === "formBasics::onAddItem") {
            const newAddedRepeater = $(event.target).find(".field-repeater-item").last();
            let repeaterIndex = -1;
            let $thisRepeater = null;
            console.log({newAddedRepeater});
            if (typeof newAddedRepeater === "object" && newAddedRepeater.length === 1) {
                repeaterIndex = $(newAddedRepeater[0]).index();
                $thisRepeater = $(".field-repeater-item").eq(repeaterIndex);
                $thisRepeaterInner = $thisRepeater.find(".projectcolor-container-inner");
            }
            setSaveValues($thisRepeaterInner);
            setColors($thisRepeaterInner);
        }
        // TODO: get move item
        if (
            context.handler === 'formBasics::onRemoveItem' ||
            context.handler === 'formBasics::onDuplicateItem'
        ) {
            const context = 'editItem';
            setSaveValues(null, context);
        }
    });

    // on un/check color onClick
    $(document).on("click", ".project-color__entry .storm-icon-pseudo", function() {
        const $closestCheckbox = $(this).closest(".custom-checkbox");
        const $thisCheckbox = $closestCheckbox.find(".project-color-checkbox");
        const $closestInnerContainer = $(this).closest(".projectcolor-container-inner");
        const checkedValue = $closestInnerContainer.prop("id").replace("projectcolor-container_value-", "");
        if ($thisCheckbox.val() == 0) {
            $thisCheckbox.val(checkedValue);
            $thisCheckbox.attr("checked", true);
            $thisCheckbox.next("label").addClass("project-color--checked");
        } else {
            $thisCheckbox.val(0);
            $thisCheckbox.attr("checked", false);
            $thisCheckbox.next("label").removeClass("project-color--checked");
        }
    });

});
