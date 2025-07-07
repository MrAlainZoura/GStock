<!DOCTYPE html>
<html lang="en">
<!-- writing by IT House -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' 
    rel='stylesheet'>
    <link rel="icon" type="image/x-icon" href="{{asset('img/icon.jpeg')}}">    
    @vite(['resources/css/login.css','resources/js/login.js'])

</head>

<body>

    <div class="wrapper">
        <span class="rotate-bg"></span>
        <span class="rotate-bg2"></span>

        <div class="form-box login">
            <h2 class="title animation" style="--i:0; --j:21">Login <a title="Retour" href="{{route('home')}}" class="close">&times;</a></h2>
             <form action="{{route('login')}}" method="post" class="max-w-sm mx-auto">
                @csrf
                @method('post')

                <div class="input-box animation" style="--i:1; --j:22">
                    <input type="text" id="email" name="email" autocomplete="off" required>
                    <label >Email</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box animation" style="--i:2; --j:23">
                    <input type="password" name="password" autocomplete="off" required>
                    <label >Mot de passe</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn animation" style="--i:3; --j:24">Login</button>
                <div class="linkTxt animation" style="--i:5; --j:25">
                    <p>Vous n'avez pas de compte ? <a href="#" class="register-link">Créer</a></p>
                </div>
            </form>
        </div>

        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20">Bienvenue!</h2>
            @if(session('echec'))
                <p class="error-message" id="alerteLog">Identifiants incorrects. Veuillez réessayer.</p>
            @endif
            <p class="animation" style="--i:1; --j:21">Renseigner vos identifiants : email et mot de passe pour vous connecter</p>
        </div>







        <div class="form-box register">

            <h2 class="title animation" style="--i:17; --j:0">Créer un compte <a title="Retour" href="{{route('home')}}" class="close">&times;</a></h2>

            <form action="{{route('nousRejoindre')}}" method="post" autocomplete="off">
                @csrf
                @method('post')
                <div class="input-box animation" style="--i:18; --j:1">
                    <input type="text" name="name" required autocomplete="off">
                    <label >Nom utilisateur</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box animation" style="--i:19; --j:2">
                    <input type="email" name="email" required autocomplete="off">
                    <label >Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" id="password" name="password" autocomplete="off" required>
                    <label >Mot de passe</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3">
                    <input type="password" id="confirm" autocomplete="off">
                    <label >Mot de passe</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" id="createCompte" class="btn animation" style="--i:21;--j:4">Créer compte</button>

                <div class="linkTxt animation" style="--i:22; --j:5">
                    <p>Déjà un compte? <a href="#" class="login-link">Connexion</a></p>
                </div>

            </form>
        </div>

        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Merci de nous rejoindre!</h2>
            @if(session('echecRegister'))
                <p class="error-message" id="alerte">{{session('echecRegister')}}</p>
            @endif
            <p class="animation" style="--i:18; --j:1;">
                Renseigner votre nom, email et mot de passe secret puis Créer
            </p>
        </div>

    </div>

</body>

</html> 