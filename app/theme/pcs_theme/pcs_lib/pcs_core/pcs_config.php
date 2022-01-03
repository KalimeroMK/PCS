<?php

    /********************
     * PCS 상수 선언
     ********************/

    const PCS_GGL_API = '';

    const PCS_LIB  = G5_THEME_PATH.'/pcs_lib/pcs_core';
    const PCS_DATA = G5_THEME_PATH.'/pcs_lib/pcs_data';

    const PCS_DATA_PHOTO = PCS_DATA.'/photo';
    const PCS_DATA_DAILY = PCS_DATA.'/photo/daily';
    const PCS_PHOTO_PT1  = PCS_DATA.'/photo/photo_1';
    const PCS_PHOTO_PT2  = PCS_DATA.'/photo/photo_2';
    const PCS_PHOTO_SPL  = PCS_DATA.'/photo/spool';
    const PCS_PHOTO_TP   = PCS_DATA.'/photo/tp';
    const PCS_PHOTO_PKG  = PCS_DATA.'/photo/package';
    const PCS_DWG_ISO    = PCS_DATA.'/dwg_iso';
    const PCS_DWG_PKG    = PCS_DATA.'/dwg_pkg';
    const PCS_PNID_PDF   = PCS_DATA.'/pnid';
    const PCS_PLAN_PDF   = PCS_DATA.'/plan/piping';
    const PCS_WORK_PDF   = PCS_DATA.'/plan/working';
    const PCS_PNID_MST   = PCS_PNID_PDF.'/master';
    const PCS_EQUI_PDF   = PCS_DATA.'/equipment';
    const PCS_PKG_PDF    = PCS_DATA.'/pkg/scaned';
    const PCS_REP_PDF    = PCS_DATA.'/report';

    const PCS_LIB_URL  = G5_URL.'/'.G5_THEME_DIR.'/pcs_theme/pcs_lib';
    const PCS_CORE_URL = PCS_LIB_URL.'/pcs_core';
    const PCS_WPV_URL  = PCS_LIB_URL.'/pdfjs_viewer/web';

    const PCS_DATA_URL = PCS_LIB_URL.'/pcs_data';

    const PCS_ISO_URL = PCS_DATA_URL.'/dwg_iso';
//define('PCS_FAB_URL',		PCS_DATA_URL.'/dwg_fab');
    const PCS_PKG_URL  = PCS_DATA_URL.'/dwg_pkg';
    const PCS_PNID_URL = PCS_DATA_URL.'/pnid';
    const PCS_MPD_URL  = PCS_PNID_URL.'/master';
    const PCS_PLAN_URL = PCS_DATA_URL.'/plan/piping';
    const PCS_WORK_URL = PCS_DATA_URL.'/plan/working';

    const PCS_URL_PHOTO = PCS_DATA_URL.'/photo';
    const PCS_URL_DAILY = PCS_URL_PHOTO.'/daily';
    const PCS_URL_PT1   = PCS_URL_PHOTO.'/photo_1';
    const PCS_URL_PT2   = PCS_URL_PHOTO.'/photo_2';
    const PCS_URL_SPL   = PCS_URL_PHOTO.'/spool';
    const PCS_URL_PKG   = PCS_URL_PHOTO.'/package';