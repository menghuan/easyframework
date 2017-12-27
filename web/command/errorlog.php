<?php
/**
 * 记录错误日志
 */
function exceptionHandler() {
    error_reporting(E_ALL ^ E_NOTICE);
    date_default_timezone_set('Asia/Shanghai');//设置时区
    ini_set('display_errors', 0);    //将错误记录到日志
    define('UC_LOGDIR', UC_ROOT.'./log/');
    ini_set('error_log', UC_LOGDIR . date('Ymd') . '_errors.txt');
    ini_set('log_errors', 1);    //开启错误日志记录
    ini_set('ignore_repeated_errors', 1);//不重复记录出现在同一个文件中的同一行代码上的错误信息。
    $user_defined_err = error_get_last();
    if ($user_defined_err['type'] > 0) {
        switch ($user_defined_err['type']) {
            case 1:
                $user_defined_errType = '致命的运行时错误(E_ERROR)';
                break;
            case 2:
                $user_defined_errType = '非致命的运行时错误(E_WARNING)';
                break;
            case 4:
                $user_defined_errType = '编译时语法解析错误(E_PARSE)';
                break;
            case 8:
                $user_defined_errType = '运行时提示(E_NOTICE)';
                break;
            case 16:
                $user_defined_errType = 'PHP内部错误(E_CORE_ERROR)';
                break;
            case 32:
                $user_defined_errType = 'PHP内部警告(E_CORE_WARNING)';
                break;
            case 64:
                $user_defined_errType = 'Zend脚本引擎内部错误(E_COMPILE_ERROR)';
                break;
            case 128:
                $user_defined_errType = 'Zend脚本引擎内部警告(E_COMPILE_WARNING)';
                break;
            case 256:
                $user_defined_errType = '用户自定义错误(E_USER_ERROR)';
                break;
            case 512:
                $user_defined_errType = '用户自定义警告(E_USER_WARNING)';
                break;
            case 1024:
                $user_defined_errType = '用户自定义提示(E_USER_NOTICE)';
                break;
            case 2048:
                $user_defined_errType = '代码提示(E_STRICT)';
                break;
            case 4096:
                $user_defined_errType = '可以捕获的致命错误(E_RECOVERABLE_ERROR)';
                break;
            case 8191:
                $user_defined_errType = '所有错误警告(E_ALL)';
                break;
            default:
                $user_defined_errType = '未知类型';
                break;
        }
        $msg = sprintf('%s %s %s %s %s', date("Y-m-d H:i:s"), $user_defined_errType, $user_defined_err['message'], $user_defined_err['file'], $user_defined_err['line']);
        error_log($msg, 0);
    }
}
register_shutdown_function('exceptionHandler');
?>
