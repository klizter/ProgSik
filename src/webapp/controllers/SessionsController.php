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
        # post isadmin? 

        if ($this->auth->checkCredentials($user, $pass)) {
            $_SESSION['user'] = $user;
            setcookie("user", $user);
            #sette på secure og httpOnly flag
            setcookie("password", $pass, 0, '', '', true); #fjerne denne
            $isAdmin = $this->auth->user()->isAdmin();

            # fjerne dette
            #sette på secure og httpOnly flag
            # setcookie( name, value, expire, path, domain, secure, httponly);
            # setcookie( 'UserName', 'Bob', 0, '/forums', 'www.example.com', isset($_SERVER["HTTPS"]), true);
            if ($isAdmin) {
                setcookie("isadmin", "yes", 0, '', '', false, true);
            } else {
                setcookie("isadmin", "no", 0, '', '', false, true);
            }

            $this->app->flash('info', "You are now successfully logged in as $user.");
            $this->app->redirect('/');
            return;
        }

        $this->app->flashNow('error', 'Incorrect user/pass combination.');
        $this->render('sessions/new.twig', []);
    }

    public function destroy()
    {
        $this->auth->logout();
        $this->app->redirect('http://www.ntnu.no/');
    }
}
