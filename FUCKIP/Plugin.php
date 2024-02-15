<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Aixiaoji-FUCKIP
 * 被苏珊了怎么办？试试Fuck他的ip(配置提示请直接修改插件)
 *
 * @package FUCKIP
 * @author Aixiaoji
 * @version 1.0.0
 * @link http://aixiaoji.blog.nbmax.top/
 */

class FUCKIP_Plugin implements Typecho_Plugin_Interface
{
    // 激活插件
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->beforeRender = array('FUCKIP_Plugin', 'checkIP');
    }

    // 禁用插件
    public static function deactivate()
    {
        // 禁用操作
    }

    // 插件配置面板
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $ips = new Typecho_Widget_Helper_Form_Element_Text('ips', NULL, '', '被Fuck的IPs', '使用{英文逗号（,）}分割');
        $form->addInput($ips);
    }

    // 个人用户的配置面板
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // 个人配置面板
    }

    // 检查用户 IP 地址
    public static function checkIP($archive, $select)
    {
        $options = Typecho_Widget::widget('Widget_Options')->plugin('FUCKIP');
        $blockedIPs = $options->ips ? explode(',', $options->ips) : array();

        $userIP = self::getUserIP();
        if (in_array($userIP, $blockedIPs)) {
            $archive->response->throwJson(array(
                'status' => 'error',
                'message' => $options->blockedMessage ? $options->blockedMessage : 'You are forbidden to access this site.（傻鸟，你IP被我禁了！：请联系Aixiaoji2020@163.com）'
            ));
            exit;
        }
    }

    // 获取用户 IP 地址
    public static function getUserIP()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
}
