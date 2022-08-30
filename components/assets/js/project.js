const addAlpha = function(color, opacity) {
    var _opacity = Math.round(Math.min(Math.max(opacity || 1, 0), 1) * 255);
    return color + _opacity.toString(16).toUpperCase();
};

const runProjectPopUpCounter = function(popUpCounter, thisLinkUrl) {
    const POP_UP_MINUS_COUNTER_RANDOM = -3;

    const popUpCounterMinus = randomIntFromInterval(POP_UP_MINUS_COUNTER_RANDOM, 0);
    let thisPopUpCounter = popUpCounter;
    const popUpCounterTimeout = setInterval(() => {
        thisPopUpCounter = thisPopUpCounter - 1;
        $('#mm-only-body-bs-modal .modal-popup-counter').text(thisPopUpCounter);
        if (thisPopUpCounter === popUpCounterMinus) {
            $('#mm-only-body-bs-modal').hide();
            $('body').removeClass('modal-open');
            $('body').css({
                'overflow' : '',
                'padding-right' : ''
            });
            $('.modal-backdrop').remove();
            const openLinkTimeout = setTimeout(() => {
                clearTimeout(openLinkTimeout);
                window.open(thisLinkUrl, '_blank').focus();
            }, 100);
            clearInterval(popUpCounterTimeout);
        }
    }, 1000);
}

$(function() {

    $('.project-basics-item').each(function() {
        const $basicItem = $(this);
        if ($basicItem.prev('.projet-basic-pop-up-counter')) {
            const popUpValue = parseInt($basicItem.prev('.projet-basic-pop-up-counter').val());
            const $popUpLink = $basicItem.find('.projet-basic-pop-up');
            $popUpLink.on('click', function(event) {
                event.preventDefault();
                const thisLinkUrl = $(this).prop('href');

                if (popUpValue) {
                    $('#mm-only-body-bs-modal').find('.modal-body').html(`
                        <div class="pop-up-modal-container">
                            <h2 class="pop-up-modal-title">
                                Your link is going to open in
                                <span class="modal-popup-counter">${popUpValue}</span>
                                seconds!
                            </h2>
                            <p class="pop-up-modal-text">
                                Be prepared!
                            </p>
                        </div>
                    `);
                    $('#mm-only-body-bs-modal').modal('show');
                    runProjectPopUpCounter(popUpValue, thisLinkUrl);
                }

            });
        }

    });

});
