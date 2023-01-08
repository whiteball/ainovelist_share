<?php

namespace App\Controllers;

use App\Models\Action_log;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;

use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form'];

    /**
     * @var Session
     */
    protected $session;

    /**
     * ログインユーザーID。ログイン状態なら0以外が入る。
     *
     * @var int
     */
    protected $loginUserId = 0;

    /**
     * @var Action_log
     */
    protected $action_log;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        if ($this->_isLoggedIn()) {
            $this->loginUserId = (int) $_SESSION['login'];
        }

        $this->action_log = model(Action_log::class);

        // 全年齢でR-18閲覧の確認ダイアログを出さないようにするためにリセット
        $this->session->unmarkFlashdata('nsfw_mode_confirm');
        unset($_SESSION['nsfw_mode_confirm']);

        // ソートの記憶
        $sort_mode = $this->request->getGet('sort');
        if (! empty($sort_mode)) {
            $_SESSION['sort_mode'] = $sort_mode;
        }

        // R-18閲覧の記憶
        $nsfw_mode = $this->request->getGet('nmode');
        if (! empty($nsfw_mode)) {
            $_SESSION['nsfw_mode'] = $nsfw_mode;
        }

        // リスト表示モードの記憶
        $list_mode = $this->request->getGet('lmode');
        if (! empty($list_mode)) {
            if ($list_mode === 's' || (in_array($list_mode, ['a', 'n'], true) && ($_SESSION['nsfw_mode'] ?? 's') !== 's')) {
                $_SESSION['list_mode'] = $list_mode;
            } else {
                // R-18閲覧の確認ダイアログを出す設定
                $_SESSION['nsfw_mode_confirm'] = true;
                $this->session->markAsFlashdata('nsfw_mode_confirm');
            }
        }
    }

    public function isPost()
    {
        return $this->request->getMethod() === 'post';
    }

    protected function _isLoggedIn()
    {
        return isset($_SESSION['login']);
    }

    protected function _isNotLoggedIn()
    {
        return ! $this->_isLoggedIn();
    }
}
