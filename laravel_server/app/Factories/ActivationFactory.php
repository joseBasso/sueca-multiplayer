<?php

namespace App\Factories;

use App\User;
use App\Repositories\ActivationRepository;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class ActivationFactory
{
    protected $activationRepo;
    protected $mailer;
    protected $resendAfter = 24;

    public function __construct(ActivationRepository $activationRepo, Mailer $mailer)
    {
        $this->activationRepo = $activationRepo;
        $this->mailer = $mailer;
    }

    public function sendActivationMail($user)
    {
        if (!$user->blocked || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->activationRepo->createActivation($user);

        $link = route('user.activate', $token);
        $message = sprintf('Hi! Please activate your account: %s', $link, $link);

        $this->mailer->raw($message, function (Message $m) use ($user) {
            $m->to($user->email)->subject('Activation email');
        });
    }

    public function sendBlockedUnblockedMail($user){
      if ($user->blocked) {
        $message = sprintf('You have been blocked. Reason: %s', $user->reason_blocked);
      } else if (!$user->blocked){
        $message = sprintf('You have been unblocked. Reason: %s', $user->reason_reactivated);
      }
      $this->mailer->raw($message, function (Message $m) use ($user) {
          $m->to($user->email)->subject('User Changed');
      });

    }

    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $user = User::find($activation->user_id);

        $user->blocked = false;

        $user->save();

        $this->activationRepo->deleteActivation($token);

        return $user;
    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}
