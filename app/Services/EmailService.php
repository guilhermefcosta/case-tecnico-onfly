<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService 
{

    public static function sendEmailExpense(Expense $expense)
    {
        $admUsersEmails = User::where('role', '1')->get('email');
        
        foreach($admUsersEmails as $email) {
            Mail::send('email.despesacriada', ['expense' => $expense, 'card' => $expense->card, 'user' => $expense->card->user], function ($m) use ($email) {
                $m->from('emailtesteonfly@gmail.com');
                $m->to($email['email']);
                $m->subject("Despesa criada - Case técnico Onfly - Guilherme Ferreira");
            });
        }

        /* envia email para usuario dono da despesa caso ele seja adm */
        if ($expense->card->user->role == 2) {
            Mail::send('email.despesacriada', ['expense' => $expense, 'card' => $expense->card, 'user' => $expense->card->user], function ($m) use ($expense) {
                $m->from('emailtesteonfly@gmail.com');
                $m->to($expense->card->user->email);
                $m->subject("Despesa criada - Case técnico Onfly - Guilherme Ferreira");
            });
        }


    }


}