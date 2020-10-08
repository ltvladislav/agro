
/*********************
 * Section for creating in file function
 * eg:
 *      rendering custom html from json,
 *      load and resize some specific elements on page,
 *      and other
 * Initialize examples:
 *********************/


/*********************
 * Section import custom functions
 * Import examples:
 *********************/

import {
    isExist,
    createEvent
} from './delta-functions'

import TableViewModel from "./tableGenerate"
// import "./lazy-load"
// import lozad from 'lozad'

/*********************
 * initialize some site properties
 *********************/

let nextArrow = `<svg class="next slide-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
            <g fill="none">
                <path d="M25,0A25,25,0,1,1,0,25,25,25,0,0,1,25,0Z" stroke="none"/>
                <path d="M 25 1 C 21.75958061218262 1 18.61661911010742 1.634418487548828 15.65842056274414 2.885631561279297 C 12.80064010620117 4.094371795654297 10.23387908935547 5.825000762939453 8.029441833496094 8.029441833496094 C 5.825000762939453 10.23387908935547 4.094371795654297 12.80064010620117 2.885631561279297 15.65842056274414 C 1.634418487548828 18.61661911010742 1 21.75958061218262 1 25 C 1 28.24041938781738 1.634418487548828 31.38338088989258 2.885631561279297 34.34157943725586 C 4.094371795654297 37.19935989379883 5.825000762939453 39.76612091064453 8.029441833496094 41.97055816650391 C 10.23387908935547 44.17499923706055 12.80064010620117 45.90563201904297 15.65842056274414 47.1143684387207 C 18.61661911010742 48.36558151245117 21.75958061218262 49 25 49 C 28.24041938781738 49 31.38338088989258 48.36558151245117 34.34157943725586 47.1143684387207 C 37.19935989379883 45.90563201904297 39.76612091064453 44.17499923706055 41.97055816650391 41.97055816650391 C 44.17499923706055 39.76612091064453 45.90563201904297 37.19935989379883 47.1143684387207 34.34157943725586 C 48.36558151245117 31.38338088989258 49 28.24041938781738 49 25 C 49 21.75958061218262 48.36558151245117 18.61661911010742 47.1143684387207 15.65842056274414 C 45.90563201904297 12.80064010620117 44.17499923706055 10.23387908935547 41.97055816650391 8.029441833496094 C 39.76612091064453 5.825000762939453 37.19935989379883 4.094371795654297 34.34157943725586 2.885631561279297 C 31.38338088989258 1.634418487548828 28.24041938781738 1 25 1 M 25 0 C 38.80712127685547 0 50 11.19287872314453 50 25 C 50 38.80712127685547 38.80712127685547 50 25 50 C 11.19287872314453 50 0 38.80712127685547 0 25 C 0 11.19287872314453 11.19287872314453 0 25 0 Z" stroke="none" fill="currentColor"/>
            </g>
            <path d="M10.3,10.266,2.24,18.3A1.31,1.31,0,0,1,.384,16.45L7.512,9.341.384,2.234A1.31,1.31,0,0,1,2.24.383L10.3,8.417a1.306,1.306,0,0,1,0,1.85Z" transform="translate(19.66 15.656)" fill="currentColor"/>
        </svg>`;
let prevArrow = `<svg class="prev slide-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
            <g fill="none">
                <path d="M25,0A25,25,0,1,1,0,25,25,25,0,0,1,25,0Z" stroke="none"/>
                <path d="M 25 1 C 21.75958061218262 1 18.61661911010742 1.634418487548828 15.65842056274414 2.885631561279297 C 12.80064010620117 4.094371795654297 10.23387908935547 5.825000762939453 8.029441833496094 8.029441833496094 C 5.825000762939453 10.23387908935547 4.094371795654297 12.80064010620117 2.885631561279297 15.65842056274414 C 1.634418487548828 18.61661911010742 1 21.75958061218262 1 25 C 1 28.24041938781738 1.634418487548828 31.38338088989258 2.885631561279297 34.34157943725586 C 4.094371795654297 37.19935989379883 5.825000762939453 39.76612091064453 8.029441833496094 41.97055816650391 C 10.23387908935547 44.17499923706055 12.80064010620117 45.90563201904297 15.65842056274414 47.1143684387207 C 18.61661911010742 48.36558151245117 21.75958061218262 49 25 49 C 28.24041938781738 49 31.38338088989258 48.36558151245117 34.34157943725586 47.1143684387207 C 37.19935989379883 45.90563201904297 39.76612091064453 44.17499923706055 41.97055816650391 41.97055816650391 C 44.17499923706055 39.76612091064453 45.90563201904297 37.19935989379883 47.1143684387207 34.34157943725586 C 48.36558151245117 31.38338088989258 49 28.24041938781738 49 25 C 49 21.75958061218262 48.36558151245117 18.61661911010742 47.1143684387207 15.65842056274414 C 45.90563201904297 12.80064010620117 44.17499923706055 10.23387908935547 41.97055816650391 8.029441833496094 C 39.76612091064453 5.825000762939453 37.19935989379883 4.094371795654297 34.34157943725586 2.885631561279297 C 31.38338088989258 1.634418487548828 28.24041938781738 1 25 1 M 25 0 C 38.80712127685547 0 50 11.19287872314453 50 25 C 50 38.80712127685547 38.80712127685547 50 25 50 C 11.19287872314453 50 0 38.80712127685547 0 25 C 0 11.19287872314453 11.19287872314453 0 25 0 Z" stroke="none" fill="currentColor"/>
            </g>
            <path d="M10.3,10.266,2.24,18.3A1.31,1.31,0,0,1,.384,16.45L7.512,9.341.384,2.234A1.31,1.31,0,0,1,2.24.383L10.3,8.417a1.306,1.306,0,0,1,0,1.85Z" transform="translate(19.66 15.656)" fill="currentColor"/>
        </svg>`;

/*********************
 * Section import jquery and libs
 * Import examples:
 *********************/
import $ from "jquery";

// window.$ = window.jQuery = $;
require("slick-carousel");
require("@fancyapps/fancybox");
// import "jquery-ui/ui/widgets/slider";
// import "jquery-ui/ui/widgets/datepicker";
// import "jquery-ui-touch-punch";
// import "jquery.maskedinput/src/jquery.maskedinput"

// require("objectFitPolyfill");
//
// $.fancybox.defaults.loop = "true";
/*********************
 * Section import vanilla JS libs
 * Import examples:
 *********************/
// import {TweenMax} from "gsap/TweenMax";
// import TimelineMax from "gsap/TimelineMax";
// import PhotoSphereViewer from "photo-sphere-viewer"
// import ScrollToPlugin from "gsap/ScrollToPlugin"
// let scrollTo = ScrollToPlugin;
// import ScrollMagic from 'ScrollMagic';
// import 'scrollmagic/scrollmagic/uncompressed/plugins/debug.addIndicators';
// import 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap';
// import SlimSelect from 'slim-select'
// import GoogleMap from "./googleMapModule";
// import AxiosGet from "./_system-classes/AxiosGet";
// import debounce from "lodash.debounce";

/*********************
 * Section Initialize some constant and manipulate DOM
 * Initialize examples:
 *********************/

if (!window.site.hasOwnProperty("headerHeight")) {
    let header = document.querySelector('.main-wrapper header');
    window.site.headerHeight = header.getBoundingClientRect().height;
    header.setAttribute('data-height', window.site.headerHeight);
    window.addEventListener('resize', function () {
        window.site.headerHeight = header.getBoundingClientRect().height;
        header.setAttribute('data-height', window.site.headerHeight);
    });
    window.addEventListener('scroll', function () {
        window.site.headerHeight = header.getBoundingClientRect().height;
        header.setAttribute('data-height', window.site.headerHeight);
    });
}

document.addEventListener('DOMContentLoaded', function (event) {

    loadAndResize();

    isExist('.editor-content > table', () => {
        document.querySelectorAll('.editor-content > table').forEach((table, index) => {
            addTableWrap(table, index)
        })
    });

    isExist('.main-slider', () => {
        let slider = document.querySelector('.main-slider');

        $(slider).slick({
            infinite: true,
            dots: false,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            autoplay: true,
            autoplaySpeed: 3800,
            pauseOnHover: false
        })
    });

    isExist('.partners-slider', () => {
        let slider = document.querySelector('.partners-slider');

        $(slider).slick({
            infinite: true,
            dots: false,
            prevArrow: prevArrow,
            nextArrow: nextArrow,
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplaySpeed: 3000,
            pauseOnHover: false
        })
    })

    isExist('.tvariniztvo-page', () => {
        let table = new TableViewModel({
            schemaName: "tabl1",
            generateTo: "table-tvarin",
            cssClass: "table_dark"
        });
    })
});

createEvent(window, 'resize', loadAndResize);

createEvent(window, 'scroll', function (e) {});


function loadAndResize() {
    isExist('main', function () {
        let footerHeight = document.querySelector('footer').getBoundingClientRect().height;
        document.querySelector('header').style.position = "fixed";
        document.querySelector('main').style.paddingTop = window.site.headerHeight + 'px';
        document.querySelector('main').style.minHeight = window.innerHeight + 'px';
    });

    isExist('.main-slider', function () {
        document.querySelector('.main-slider').style.height = window.innerHeight - window.site.headerHeight + 'px';
        document.querySelector('.main-slider-wrap').style.height = window.innerHeight - window.site.headerHeight + 'px';
    });

    isExist('.gallery', () => {
    })

}

function setEqualHeight(slider, selector) {
    let maxHeight = 0;
    slider.querySelectorAll(selector).forEach((slide) => {
        if (maxHeight < slide.getBoundingClientRect().height) {
            maxHeight = slide.getBoundingClientRect().height;
        }
    });

    slider.querySelectorAll(selector).forEach((slide) => {
        slide.style.height = maxHeight + 'px';
    })

}

function onOpenModal() {
    document.documentElement.style.overflow = 'hidden';
    document.documentElement.style.marginRight = `${window.site.scrollBarWidth}px`;
    document.querySelector('header').style.marginRight = `${window.site.scrollBarWidth}px`;
}

function onCloseModal() {

    document.documentElement.style.overflow = '';
    document.documentElement.style.marginRight = ``;
    document.querySelector('header').style.marginRight = ``;

}

function addTableWrap(table, index) {
    let wrapper = document.createElement('div');
    wrapper.setAttribute('data-table-index', index);
    wrapper.classList.add('table-wrap');

    let caption = table.caption ? `<h3>${table.caption.innerHTML}</h3>` : '';
    table.insertAdjacentHTML('beforebegin', caption);
    table.insertAdjacentElement('beforebegin', wrapper);

    wrapper.appendChild(table)
}


