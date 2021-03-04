#!/bin/bash

shopt -s expand_aliases
source ~/.bash_profile


function build_theme() {
    set -x
    php bin/magento setup:static-content:deploy en_GB
    php bin/magento setup:static-content:deploy --area adminhtml en_US
}

function enable_productionnocomp() {
    set -x
    php bin/magento deploy:mode:set -s production
}

function compile_code() {
    set -x
    php -dmemory_limit=2G bin/magento setup:di:compile
}

function enable_developermode() {
    set -x
    php bin/magento deploy:mode:set -s development
}

function clear_cache() {
    set -x
    php bin/magento c:c
}

function clear_all() {
    set -x
    rm -rf var/di/* var/generation/* var/cache/* var/page_cache/* var/view_preprocessed/* var/composer_home/cache/* pub/static/*
    rm -rf generated/*
}

function clear() {
    set -x
    rm -rf var/di/* var/generation/* var/cache/* var/page_cache/* var/view_preprocessed/* var/composer_home/cache/* pub/static/*
    rm -rf generated/*
}

function enable_cache_all() {
    set -x
    php bin/magento cache:enable
}

function disable_cache_all() {
    set -x
    php bin/magento cache:disable
}

function clear_less() {
     set -x
     rm -rf pub/static/frontend/Pearl/weltpixel_custom/en_GB
}

function clear_css() {
    set -x
    find pub/static/frontend/Pearl/weltpixel_custom/en_GB -type f -name '*.css' -delete
}



function build_themes() {
    set -x
    php bin/magento setup:static-content:deploy en_GB
}

search_dir='./'

PS3='Please enter your choice: '
options=("Quit" "Clear Var Di/Generation/Caches" "Clear Cache" "Clear All Var/Di/Gen/Cache"  "Clear Entire Theme Folder" "Clear Generated CSS" "Build Themes" "Disable All Cache" "Enable All Cache" "Enable Production Mode - Skip Compliation"  "Enable Development Mode"  "Setup:Di:Compile" "Build Themes" )
select opt in "${options[@]}"
do
    case $opt in
        "Quit")
            break
            ;;
        "Clear Var Di/Generation/Caches")
            clear
            ;;
        "Clear Cache")
            clear_cache
            ;;
        "Clear All Var/Di/Gen/Cache")
            clear_all;
            ;;
        "Clear Entire Theme Folder")
            clear_less;
            ;;
        "Clear Generated CSS")
            clear_css;
            ;;
        "Build Themes")
            build_theme
            ;;
        "Disable All Cache")
            disable_cache_all;
            ;;
        "Enable All Cache")
            enable_cache_all;
            ;;
        "Enable Production Mode - Skip Compliation")
           enable_productionnocomp;
           ;;
        "Enable Development Mode")
           enable_developermode;
           ;;
        "Setup:Di:Compile")
           compile_code;
           ;;
        "Build Themes")
           build_themes;
           ;;
        *) echo invalid option;;
    esac
done
