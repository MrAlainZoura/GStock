<?php

namespace App\Http\Controllers;

use App\Mail\RapportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail($to, $object, array $contenu){
        // $to = "a.tshiyanze@gmail.com";
        // $object="Envoi des nudes à damso";
        // $contenu ="Bonjour mr Zoura nous vous esperons en bonne santé. Ceci est un rappl pour vous presenter au bureau demain à 8H pour une reunion d'affaire avec le DG de NolyCorp";

        $send = Mail::to($to)->send(new RapportMail($object, $contenu));
        if($send){
            return true;
        }else{
            return false;
        }
        
    }

    public function sendRapportMail($to, $object, array $contenu){
        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $domain = substr(strrchr($to, "@"), 1); // extrait le domaine après @

            if (checkdnsrr($domain, "MX")) {
                try {
                    Mail::to($to)->send(new RapportMail($object, $contenu));
                    return true;
                } catch (\Exception $e) {
                    return false; // erreur lors de l'envoi
                }
            } else {
                return false; // domaine ne peut pas recevoir d'emails
            }
        } else {
            return false; // format d'adresse invalide
        }
    }
}
