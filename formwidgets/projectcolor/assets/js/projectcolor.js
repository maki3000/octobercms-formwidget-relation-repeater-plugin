$(function() {

    console.log("init by formwidget");

    // set repeater save values on init
    if (typeof setSaveValues === "function") {
        setSaveValues();
    }
    // set repeater values on init
    if (typeof setValues === "function") {
        setValues();
    }
    // set repeater colors on init
    if (typeof setColors === "function") {
        setColors();
    }

});
