<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\repository\UserRepository;

class SessionsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function newSession()
    {
        if ($this->auth->check()) {
            $username = $this->auth->user()->getUsername();
            $this->app->flash('info', 'You are already logged in as ' . $username);
            $this->app->redirect('/');
            return;
        }

        $this->render('sessions/new.twig', []);
    }

    public function create()
    {
        $request = $this->app->request;
        $user    = $request->post('user');
        $pass    = $request->post('pass');
        $login_atempts = $this->userRepository->getLoginAtempts($user);
        $time_stamp = $this->userRepository->getTimeOut($user);

        if(!is_null($time_stamp) and date("Y-m-d H:i:s") > date($time_stamp)){
            $this->userRepository->clearTimeOut($user);
            $this->userRepository->clearLoginAtempts($user);
            $login_atempts = $this->userRepository->getLoginAtempts($user);
            $time_stamp = $this->userRepository->getTimeOut($user);
        }

        if(is_null($time_stamp) or date("Y-m-d H:i:s") > date($time_stamp)){

            $this->userRepository->updateLoginAtempts($user);

            if ($this->auth->checkCredentials($user, $pass)) {
                $_SESSION['user'] = $user;
                setcookie("user", $user);
                setcookie("password",  $pass);
                $isAdmin = $this->auth->user()->isAdmin();

                if ($isAdmin) {
                    setcookie("isadmin", "yes");
                } else {
                    setcookie("isadmin", "no");
                }

                $this->userRepository->clearTimeOut($user);
                $this->userRepository->clearLoginAtempts($user);

                $this->app->flash('info', "You are now successfully logged in as $user.");
                $this->app->redirect('/');
                return;
            }else{
                $this->app->flashNow('error', 'Incorrect user/pass combination.');
            }
        }else{
            $this->app->flashNow('error', 'You have typed in password to many times. You are timed out and unavailable to log in for 1 minute.');
        }

        $this->render('sessions/new.twig', []);
    }

    public function destroy()
    {
        $this->auth->logout();
        $this->app->redirect('/');
    }
}
