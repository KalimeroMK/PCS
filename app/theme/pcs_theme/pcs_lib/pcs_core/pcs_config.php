<?php

/********************
    PCS 상수 선언
********************/

define('PCS_GGL_API',	'AIzaSyB-pS0Oc_Ifs3S5PI6RF9VPyH15z3LLtAs');

define('PCS_LIB',	G5_THEME_PATH.'/pcs_lib/pcs_core');
define('PCS_DATA',	G5_THEME_PATH.'/pcs_lib/pcs_data');

define('PCS_DATA_PHOTO',	PCS_DATA.'/photo');
define('PCS_DATA_DAILY',	PCS_DATA.'/photo/daily');
define('PCS_PHOTO_PT1',		PCS_DATA.'/photo/photo_1');
define('PCS_PHOTO_PT2',		PCS_DATA.'/photo/photo_2');
define('PCS_PHOTO_SPL',		PCS_DATA.'/photo/spool');
define('PCS_PHOTO_TP',		PCS_DATA.'/photo/tp');
define('PCS_PHOTO_PKG',		PCS_DATA.'/photo/package');
define('PCS_DWG_ISO',		PCS_DATA.'/dwg_iso');
//define('PCS_DWG_FAB',		PCS_DATA.'/dwg_fab');
define('PCS_DWG_PKG',		PCS_DATA.'/dwg_pkg');
define('PCS_PNID_PDF',		PCS_DATA.'/pnid');
define('PCS_PLAN_PDF',		PCS_DATA.'/plan/piping');
define('PCS_WORK_PDF',		PCS_DATA.'/plan/working');
define('PCS_PNID_MST',		PCS_PNID_PDF.'/master');
define('PCS_EQUI_PDF',		PCS_DATA.'/equipment');
define('PCS_PKG_PDF',		PCS_DATA.'/pkg/scaned');
define('PCS_REP_PDF',		PCS_DATA.'/report');


define('PCS_LIB_URL',		G5_URL.'/'.G5_THEME_DIR.'/pcs_theme/pcs_lib');
define('PCS_CORE_URL',		PCS_LIB_URL.'/pcs_core');
define('PCS_WPV_URL',		PCS_LIB_URL.'/pdfjs_viewer/web');

define('PCS_DATA_URL',		PCS_LIB_URL.'/pcs_data');

define('PCS_ISO_URL',		PCS_DATA_URL.'/dwg_iso');
//define('PCS_FAB_URL',		PCS_DATA_URL.'/dwg_fab');
define('PCS_PKG_URL',		PCS_DATA_URL.'/dwg_pkg');
define('PCS_PNID_URL',		PCS_DATA_URL.'/pnid');
define('PCS_MPD_URL',		PCS_PNID_URL.'/master');
define('PCS_PLAN_URL',		PCS_DATA_URL.'/plan/piping');
define('PCS_WORK_URL',		PCS_DATA_URL.'/plan/working');

define('PCS_URL_PHOTO',		PCS_DATA_URL.'/photo');
define('PCS_URL_DAILY',		PCS_URL_PHOTO.'/daily');
define('PCS_URL_PT1',		PCS_URL_PHOTO.'/photo_1');
define('PCS_URL_PT2',		PCS_URL_PHOTO.'/photo_2');
define('PCS_URL_SPL',		PCS_URL_PHOTO.'/spool');
define('PCS_URL_PKG',		PCS_URL_PHOTO.'/package');


?>