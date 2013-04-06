<?php
define('__MLC_TRACKING__', dirname(__FILE__));

define('__MLC_TRACKING_CORE__', __MLC_TRACKING__ . '/_core');
define('__MLC_TRACKING_CORE_CTL__', __MLC_TRACKING_CORE__ . '/ctl');
define('__MLC_TRACKING_CORE_MODEL__', __MLC_TRACKING_CORE__ . '/model');
define('__MLC_TRACKING_DATA_LAYER__', __MLC_TRACKING_CORE_MODEL__ . '/data_layer');
define('__MLC_TRACKING_CORE_VIEW__', __MLC_TRACKING_CORE__ . '/view');
MLCApplicationBase::$arrClassFiles['MLCEventTrackingDriver'] = __MLC_TRACKING_CORE__ . '/MLCEventTrackingDriver.class.php';


require_once(__MLC_TRACKING_CORE__ . '/_enum.inc.php');
require_once(__MLC_TRACKING_DATA_LAYER__ . '/base_classes/Conn.inc.php');

