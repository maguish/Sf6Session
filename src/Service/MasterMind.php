<?php

    namespace App\Service;

    interface iMasterMind {
        public function __construct($taille=4);
        public function test($code);
        public function getEssais();
        public function getTaille();
    }

    class MasterMind implements iMasterMind {
        //chaine de car compris entre 0 et 9 et qui sera decouvert au final
        protected $code = "";

        //on crée un tableau qui garde en mémoire tous les essais
        protected $tabEssais = array();

        //constructeur par défaut : génération d'un code aléatoire de taille
        public function __construct($taille = 4){
            for($i=0; $i < $taille; $i++){
                $c=rand(0,9);		//un chiffre aléatoire
                $this->code.="$c";  // on initialise le code avec ce chiffre aléatoire
            }
        }

        //pour vérifier si le code saisi par l'utilisateur est valide
        public function estValide($code){
            //si on a une chaine de caractère
            //ou si la taille de notre saisie est différente de celle du code attendu
            //ou si le code rentré n'est pas un entier
            if(!is_string($code) || strlen($code)!=strlen($this->code) || !ctype_digit($code)){
              return false;
            }
            //sinon
            return true;
        }

        //pour vérifier si on a fini le test
        public function estFini(){
            foreach($this->$tabEssais as $essai => $res){
                if($res['bon'] == 4){
                    return true;
                }
            }
            return false;
        }

        //tester maintenant le code
        public function test($essai){
            if(!$this->estValide($essai)){
                //si le code saisi n'est pas valide
                return false;
            } else {
                //on recupère le code vu que celui-ci est généré aleatoirement
                $cpCode = $this->code;
                //sinon, on récupère la valeur à tester
                $cpEssai = $essai;	
                //on crée un tableau pour stocker les chiffres bien et mal placés
                $res = array(
                    'proposition' => $cpEssai,
                    'bon' => 0,
                    'mal' => 0
                );

                //boucle pour trouver les chiffres bien placés
                for($i=0; $i < strlen($cpEssai); $i++){
                    if($cpCode[$i] == $cpEssai[$i]){
                        $cpCode[$i] = 'Y'; // afin de ne plus le prendre en compte
	                    $cpEssai[$i] = 'Y'; // afin de ne plus le prendre en compte
                        $res['bon']++;
                    }
                }

                //boucle pour trouver les chiffres mal placés
                for($i=0; $i < strlen($cpEssai); $i++){
                    if($cpEssai[$i] != 'Y'){
                        //on recupère la position du chiffre mal placé
                        //strpos(A,'b') cherche la position de la première occurrence de "b" dans une chaîne A
                        $pos = strpos($cpCode, $cpEssai[$i]);
                        if(!($pos === false)){
                            $res['mal']++;
                            $cpCode[$pos] = 'Y';
                        }
                    }
                }

                //on enrégistre les données de l'essai dans le tableau tabEssais
                $this->tabEssais[$essai] = $res;
                //on retourne le résultat de notre essaie
                return $res;
            }
        }

        //on retourne la taille du code
        public function getTaille() {
            return strlen($this->code);
        }
        public function getEssais() {
            return $this->tabEssais;
        }

    }
?>