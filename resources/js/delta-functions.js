let defaultConsole = console.warn;
console.warn = function (message) {
    if (typeof message === "string" && arguments.length === 1){
        defaultConsole.apply(console, ["%c"+message, "background: #222; color: #bada55; padding: 3px 10px; font-size: 15px;"]);
    } else {
        defaultConsole.apply(console, arguments);
    }
};



/*********************
 * Its a magic,
 * @param callback {function} - Function to execute when ready
 *********************/
export const onReady = (callback) => {
    document.readyState === "interactive" || document.readyState === "complete"
        ? callback()
        : document.addEventListener("DOMContentLoaded", callback, false);
};

/*********************
 *** Check parameter on string or DOM object/objects and return Array of object
 or false is not find element on page
 * @param {string | Element | NodeList} parameter  - Function to execute when ready
 * @returns {boolean | Array}
 *********************/
export const getElements = (parameter) => {
    if ((typeof parameter === "string" && typeof parameter !== "object") || parameter.constructor === String) {

        try {
            if (document.querySelectorAll(parameter).length > 0) {
                return Array.from(document.querySelectorAll(parameter));
            } else {
                return false;
            }
        } catch(e) {
            console.warn(e);
            return false
        }
    } else {
        if (parameter.constructor === NodeList) {
            return Array.from(parameter);
        } else {
            return Array.from([parameter]);
        }
    }
};

/*********************
 *** any addEventListener
 * @param {string | Element | NodeList} elements  - Function to execute when ready
 * @param {string} event - Event tpe
 * @param {function} callback - Execute function when triggered event
 * @returns {Element}
 *********************/
export const createEvent = (elements, event, callback) => {

    if (getElements(elements)) {
        getElements(elements).forEach((item) => {
            return item.addEventListener(event, callback, true);
        });
    }

};

/*********************
 *** Check device gesture
 * @returns {boolean}
 *********************/
export function isTouchDevice() {

    let prefixes = " -webkit- -moz- -o- -ms- ".split(" ");
    let mq = function (query) {
        return window.matchMedia(query).matches;
    };

    if (("ontouchstart" in window) || window.DocumentTouch && document instanceof window.DocumentTouch) {
        return true;
    }
    let query = ["(", prefixes.join("touch-enabled),("), "heartz", ")"].join('');
    console.log(query);
    return mq(query);
}

/*********************
 * @param event {object} - DOM event
 * @param block {string} - css selector of container
 * @param button {string} - css selector of button
 * @param activeClass {string} - class for check button and block state
 * @returns {void}
 *********************/
export function closeOnOutsideClick(event, block, button, activeClass) {
    if (typeof TweenMax === "undefined") throw new Error('for execute need <a href="https://www.npmjs.com/package/gsap">TweenMax</a>');

    block = getElements(block);
    button = getElements(button);

    if (!block || !button) return false;

    let isTargetBlock = checkOutsideClick(event, block, button, activeClass);
    if (isTargetBlock) {
        TweenMax.to(block, 0.25, {
            scale: 0,
            opacity: 0,
            ease: Power1.easeIn
        });
        block.classList.remove(activeClass);
        button.classList.remove(activeClass);
    }
}

/*********************
 *** Check if click out of active block and active button
 * @param event {object} - DOM event
 * @param block {string} - css selector of container
 * @param button {string} - css selector of button
 * @param activeClass {string} - class for check button and block state
 * @returns {boolean}
 *********************/
function checkOutsideClick(event, block, button, activeClass) {
    return (
        block.classList.contains(activeClass) &&
        button.classList.contains(activeClass) &&
        !block.contains(event.target) &&
        !button.contains(event.target)
    );
}

/*********************
 *** Scroll document to element
 * @param elem {object}
 * @param time {number} float in seconds
 * @param offsetRange {number}
 * @returns {undefined}
 *********************/
export function scrollToElem(elem, time = 0.4, offsetRange = 0) {

    if (elem) {
        TweenMax.to(window, time, {
            scrollTo: elem.getBoundingClientRect().top + window.scrollY - offsetRange
        });
    }

}

/*********************
 *** Set browser cookie
 * @param name
 * @param value
 * @param days
 * @returns {undefined}
 *********************/
export function createCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

/*********************
 *** Get browser cookie
 * @param {string} name
 * @returns {string | null}
 *********************/
export function readCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === " ") c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

/*********************
 *** Delete browser cookie
 * @param name
 * @returns {undefined}
 *********************/
export function eraseCookie(name) {
    createCookie(name, "", -1);
}

/*********************
 *** Replace image to background-image if not support object-fit property
 * @param elements
 * @param size
 * @param needed
 * @returns {undefined}
 *********************/
export function objectFitImages(elements, size, needed) {
    if (needed) {
        getElements(elements).forEach((item)=>{
            let src = item.getAttribute("src");
            if (src.length) {
                item.cssText = `background-image: url(${src});
                                background-size: ${size};
                                background-position: center;
                                background-repeat: no-repeat;`;
                item.setAttribute('src', '');
                item.setAttribute('alt', '');
            }
        })
    }
}

//
// $.fn.fileInputName = function (shapeStart, shapeEnd) {
//     var filePlaceholder = '',
//         wrapper = this.parent(),
//         text = wrapper.find('span');
//
//     $(wrapper).click(function () {
//         filePlaceholder = $(text).text();
//     });
//
//     this.change(function () {
//         var filename = $(this).val().replace(/^.*[\\\/]/, ''),
//             ext = filename.split('.').pop();
//         text.text();
//         console.log(ext);
//
//         wrapper.addClass('active');
//         TweenMax.to(shapeStart, 1, {
//             delay: .5,
//             morphSVG: shapeEnd
//         });
//         if ($(this).val() == "") {
//             $(text).text(filePlaceholder);
//             wrapper.removeClass('active');
//         }
//     });
// };


export class FormValidation {

    constructor(options) {
        this.option = {};

        this.option.forms = options.hasOwnProperty('forms') ? options.forms : null;
        this.option.elements = options.hasOwnProperty('elements') ? options.elements : null;
        this.option.json = options.hasOwnProperty('json') ? options.json : false;
        this.option.submitButton = options.hasOwnProperty('submitButton') ? options.submitButton : false;
        this.option.blur = options.hasOwnProperty('blur') ? options.blur : true;
        this.option.keyup = options.hasOwnProperty('keyup') ? options.keyup : false;

        this.constructor.onValidate = options.hasOwnProperty("onValidate") ? options.onValidate : this.constructor.onValidate;
        this.constructor.sendAjax = options.hasOwnProperty("sendAjax") ? options.sendAjax : this.constructor.sendAjax;
        this.constructor.afterAjax = options.hasOwnProperty("afterAjax") ? options.afterAjax : this.constructor.afterAjax;


        this.params = this.parameters;
        this.forms = [];
        this._init();
    }

    get parameters() {
        if (this.option.json) {
            return {
                dataRequired: 'required',
                dataMessage: 'message',
                dataValid: 'valid',
                errorClass: 'error'
            }
        } else {
            throw new Error('please set json params');
        }

    }

    _init() {
        this.allForms;
        this.allFormElements;

        for (let i = 0; i < this.forms.length; i++) {
            this.forms[i].status = [];

            if (this.forms[i].form && !this.option.submitButton){
                this.forms[i].event = 'submit';
                this.forms[i].form.addEventListener('submit', this._onSubmit.bind(this, this.forms[i]));
            } else {
                this.forms[i].event = 'click';
                this.forms[i].button = this.forms[i].form.querySelector(this.option.submitButton);

                this.forms[i].form
                    .querySelector(this.option.submitButton)
                    .addEventListener('click', this._onSubmit.bind(this, this.forms[i]))
            }

            for (let j = 0; j < this.forms[i].elements.length; j++) {
                if (this.option.blur && this.forms[i].elements[j]) {
                    this.forms[i].elements[j].addEventListener('blur', this._onBlur.bind(this, this.forms[i].elements[j]));
                }
                if (this.option.keyup && this.forms[i].elements[j]) {
                    this.forms[i].elements[j].addEventListener('keyup', this._onBlur.bind(this, this.forms[i].elements[j]));
                }
            }
        }
        console.log(this);
    }

    _onBlur(field, e) {
        this.checkField(field);
    }

    _onSubmit(item, e) {
        let status = item.status;

        for (let i = 0; i < item.elements.length; i++) {
            status[i] = this.checkField(item.elements[i]);
        }

        let trigger = this.constructor.checkStatus(status);

        // console.log(trigger);

        this.constructor.onValidate(trigger, item.form);

        if (trigger) {
            //return true on click submit
            if (e.target === item.button){
                return true
            }
            e.preventDefault();
            e.stopPropagation();
            return false;
        } else {

            e.preventDefault();
            e.stopPropagation();
            return false
        }

    }

    checkField(field) {
        try {

            let fieldParams = JSON.parse(field.dataset[this.option.json]),
                fieldStatus = true;


            if (fieldParams[this.params.dataValid] !== "false") {
                // console.log(fieldParams[this.params.dataValid]);
                let object = {
                    'field': field,
                    'type': this.params.dataValid,
                    'regex': fieldParams[this.params.dataValid],
                    'message': fieldParams[this.params.dataMessage]
                };
                fieldStatus = this.validateField(object);
            }

            if (!fieldStatus) return false;

            if (fieldParams[this.params.dataRequired] !== "false") {
                let object = {
                    'field': field,
                    'type': this.params.dataRequired,
                    'message': fieldParams[this.params.dataRequired]
                };
                fieldStatus = this.checkRequired(object);
            } else {
                fieldStatus = true;
            }

            // console.log(field, fieldStatus);
            return fieldStatus;

        }
        catch (e) {

            throw new Error('Invalid data-{validation parameters}, cant parse JSON array at "fieldParams"' + e );
        }
    }

    /**
     *
     * @param data {object} = combined data in this.checkField
     * @returns {boolean} - must return a true or false!!!
     */
    checkRequired(data) {
        let value = data.field.value;

        if (value === '' || value === '0' || value === null) {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true
        }
    }

    /**
     *
     * @param data {object} = combined data in this.checkField
     * @returns {boolean} - must return a true or false!!!
     */
    validateField(data) {

        let status = false;

        switch (data.regex) {
            case 'email':
                status = this.checkEmail(data);
                break;
            case 'phone_free':
                status = this.checkPhone(data);
                break;
            case 'chars':
                status = this.checkCharacters(data);
                break;
            case 'password':
                status = this.checkPassword(data);
                break;
            case 'url':
                status = this.checkUrl(data);
                break;
            default:
                new Error('Developer must defined ' + data.regex);
            // console.log('Add plz rule for ' + data.regex + ' field!!!');
        }

        return status;
    }

    checkCharacters(data) {
        let value = data.field.value,
            pattern = /^[\wа-яА-Я`ґєҐЄ´ІіЇї'.,-№\\/\s]{3,}$/;


        let testCharacters = pattern.test(value);

        if ((!testCharacters || value.length > 35) && value !== '') {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true;
        }
    }

    checkEmail(data) {
        let value = data.field.value,
            pattern = /^[^\x21-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e][a-z0-9\-\_\.\+]+@[a-z0-9\-\_\.]+\.[a-z]{2,10}$/;

        let testEmail = pattern.test(value);

        if (!testEmail && value !== '') {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true;
        }
    }

    checkPassword(data) {

        let value = data.field.value,
            pattern = /^[a-z0-9_\+!@;#$%^&*~\(\)\-]{3,20}$/;

        let testPassword = pattern.test(value);

        if ((!testPassword || value.length <= 3 || value.length >= 20) && value !== '') {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true;
        }
    }

    checkPhone(data) {
        let value = data.field.value,
            pattern = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9 \-\.]{1,12}){1,2}$/;

        let testPhone = pattern.test(value);

        if ((!testPhone || value.length < 8 || value.length > 20) && value !== '') {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true;
        }
    }

    checkUrl(data) {
        let value = data.field.value,
            pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

        let testUrl = pattern.test(value);

        if (!testUrl && value !== '') {
            this.error = data;

            return false;
        } else {
            this.done = data;

            return true;
        }
    }

    set done(data) {
        let wrapper = data.field.parentElement;

        if (data.type === this.params.dataRequired) {

            this.constructor.removeRequiredMsg(wrapper, this.params.errorClass);

        } else if (data.type === this.params.dataValid) {

            this.constructor.removeValidMsg(wrapper, this.params.errorClass);

        }
    }

    /**
     * set error on fields
     * @param data {object} = combined data in this.checkField
     */
    set error(data) {

        if (data.type === this.params.dataRequired) {

            this.constructor.insertRequiredMsg(data, this.params.errorClass);

        } else if (data.type === this.params.dataValid) {

            this.constructor.insertValidMsg(data, this.params.errorClass);

        }
    }

    static checkStatus(formStatus) {
        for (let i = 0; i < formStatus.length; i++) {
            if (!formStatus[i]) {
                return false;
            }
        }
        return true;
    }

    /**
     *  method for remove "required" error from field
     * @param wrapper DOM {object} element, (label of input)
     * @param className {string} error class
     */
    static removeRequiredMsg(wrapper, className) {
        wrapper.classList.remove(className);

        if (wrapper.querySelector('.valid_message')) {
            wrapper.removeChild(wrapper.querySelector('.valid_message'));
        }

        if (wrapper.querySelector('.required_message')) {
            wrapper.removeChild(wrapper.querySelector('.required_message'));
        }
    }

    /**
     *  method for remove "validation" error from field
     * @param wrapper DOM {object} element, (label of input)
     * @param className {string} error class
     */
    static removeValidMsg(wrapper, className) {
        wrapper.classList.remove(className);

        if (wrapper.querySelector('.required_message')) {
            wrapper.removeChild(wrapper.querySelector('.required_message'));
        }

        if (wrapper.querySelector('.valid_message')) {
            wrapper.removeChild(wrapper.querySelector('.valid_message'));
        }
    }

    static insertRequiredMsg(data, className) {
        let wrapper = data.field.parentElement;
        // let errorClass = FormValidation.params.errorClass;

        if (wrapper.querySelector('.valid_message')) {
            wrapper.removeChild(wrapper.querySelector('.valid_message'));
        }

        if (wrapper.querySelector('.required_message')) return;

        wrapper.insertAdjacentHTML('afterbegin', '<i class="required_message">' + data.message + '</i>');
        wrapper.classList.add(className);

        if (wrapper.querySelector('.required_message')) {
            wrapper.querySelector('.required_message').addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                FormValidation.removeRequiredMsg(wrapper, 'error');
                return false;
            });
        }
    }

    static insertValidMsg(data, className) {
        let wrapper = data.field.parentElement;
        // let errorClass = FormValidation.params.errorClass;

        if (wrapper.querySelector('.required_message')) {
            wrapper.removeChild(wrapper.querySelector('.required_message'));
        }

        if (wrapper.querySelector('.valid_message')) return;

        wrapper.insertAdjacentHTML('afterbegin', '<i class="valid_message">' + data.message + '</i>');
        wrapper.classList.add(className);
        //
        if (wrapper.querySelector('.valid_message')) {
            wrapper.querySelector('.valid_message').addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                FormValidation.removeValidMsg(wrapper, 'error');
                return false;
            });
        }
    }

    static onValidate() {
        console.log('nothing to exec onValidate');
    }

    static afterAjax() {
        console.log('nothing to exec afterAjax');
    }

    static sendAjax() {
        console.log('nothing to exec sendAjax or make form submit');
    }

    get allForms() {
        if (!document.querySelectorAll(this.option.forms)) return false;
        document.querySelectorAll(this.option.forms).forEach((form) => {
            this.forms.push({'form': form});
        });
    }

    get allFormElements() {
        for (let i = 0; i < this.forms.length; i++) {
            this.forms[i].elements = [];
            this.forms[i].form.querySelectorAll(this.option.elements).forEach((element, index) => {
                this.forms[i].elements.push(element);
            });
        }
    }
}

export function isExist(elements, callback) {
    // if elements = its a "css sting"
    if (typeof elements === "string") {
        elements = document.querySelectorAll(elements);
    }

    if (!elements) return false;

    if (elements.length > 0) {
        return callback(elements);
    } else {
        return false;
    }
}

//
// // export function declOfNum(number, titles)
// // {
// //     let cases = [2, 0, 1, 1, 1, 2];
// //     let cases = [2, 0, 1, 1, 1, 2];
// //     return titles[ (number%100&gt; 4 && number%100&lt;20)? 2 : cases[(number%10&lt;5)?number%10:5] ];
// // }
//
//
// export function decOfNum(number, titles) {
//     let cases = [2, 0, 1, 1, 1, 2];
//     return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
// }
