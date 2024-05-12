import { useState, useEffect, useRef } from "preact/hooks";
import { h, render as renderApp, Fragment } from "preact";

import UI from "./ui";
import toast from "./toast";
import axios from "redaxios";
import wirec from "wirec";
import api from "./api";
import App from "./app";

const pages = {};

const page = (name, component = null) => {
    if ( component === null ) {
        return pages[name];
    }

    pages[name] = component;
}

const render = (selector = "#palacify-app-root") => {
    const root = document.querySelector(selector);

    if (root) {
        renderApp(<App page={page} />, root);
    }
};

export const Lod = ({ className = "", size = false, style = {}, ...rest }) => {
    const classNames = ["palacify-icon-loading", className].filter(Boolean);

    const styles = {
        ...style,
    };

    if ( size ) {
        styles.fontSize = size;
    }

    return (<i className={classNames.join(" ")} style={styles} {...rest}>⚆</i>)
}

const palacify = {
    page,
    render,
    h,
    useState,
    useEffect,
    useRef,
    Fragment,
    wirec,
    api,
    axios,
    toast,
    Lod,
    UI
};

if ( typeof window !== "undefined" ) {

    function fix_container_height() {
        var height    = document.getElementById("wpwrap").offsetHeight;
        var container = document.querySelector("#palacify-app-root");

        Array.from(document.querySelectorAll("#wpfooter")).forEach(function(el){
            height -= el.offsetHeight;
        });

        container.style.height = height + "px";
    }

    ["resize", "load"].forEach(function(event){
        window.addEventListener( event, fix_container_height, { passive: true } );
    });

    if ( typeof window.palacify === "undefined" ) {
        window.palacify = {};
    }

    window.palacify = { ...window.palacify, ...palacify };

    palacify.render();
}