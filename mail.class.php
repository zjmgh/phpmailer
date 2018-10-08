<?php
require_once __DIR__.'/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/phpmailer/src/SMTP.php';

class Recruit_Libs_Mail
{
    public static $HOST = 'smtp.163.com'; //邮箱的服务器地址
    public static $PORT = 25; // smtp 服务器的远程服务器端口号
    public static $SMTP = ''; // 使用 ssl 加密方式登录
    public static $CHARSET = 'UTF-8'; // 设置发送的邮件的编码

    /**
     * Mailer constructor.
     */
    public function __construct($debug = false)
    {
        $this->mailer = new PHPMailer();
        $this->mailer->SMTPDebug = $debug ? 1 : 0;
        $this->mailer->isSMTP(); // 使用 SMTP 方式发送邮件
    }

    private function loadConfig()
    {
        /* Server Settings  */
        $this->mailer->SMTPAuth = true; // 开启 SMTP 认证
        $this->mailer->Host = self::$HOST; // SMTP 服务器地址
        $this->mailer->Port = self::$PORT; // 远程服务器端口号
        $this->mailer->SMTPSecure = self::$SMTP; // 登录认证方式
        /* Content Setting  */
        $this->mailer->isHTML(true); // 邮件正文是否为 HTML
        $this->mailer->CharSet = self::$CHARSET; // 发送的邮件的编码
    }

    /**
     * get all tosend mailers 
     * @return array [mailers address]
     */
    public function getAllAddress()
    {
        return $this->mailer->getToAddresses();
    }

    /**
     * set sender info
     * @param string $email    [邮箱地址]
     * @param string $username [用户名]
     * @param string $password [密码]
     * @param string $nickname [发件人昵称]
     */
    public function setSender($email, $username, $password, $nickname = '')
    {
        /* Account Settings */
        $this->mailer->Username = $username; // SMTP 登录账号
        $this->mailer->Password = $password; // SMTP 登录密码
        $this->mailer->From = $email; // 发件人邮箱地址
        !empty($nickname) and $this->mailer->FromName = $nickname; // 发件人昵称（任意内容）
        return $this;
    }

    /**
     * Add attachment
     * @param $path [附件路径]
     */
    public function addFile($path, $name)
    {
        $this->mailer->addAttachment($path, $name);
        return $this;
    }

    /**
     * Send Email
     * @param $email [收件人]
     * @param $title [主题]
     * @param $content [正文]
     * @return bool [发送状态]
     */
    public function send($email, $title, $content)
    {
        $this->loadConfig();
        $this->mailer->addAddress($email); // 收件人邮箱
        $this->mailer->Subject = $title; // 邮件主题
        $this->mailer->Body = $content; // 邮件信息
        return (bool)$this->mailer->send(); // 发送邮件
    }
}
