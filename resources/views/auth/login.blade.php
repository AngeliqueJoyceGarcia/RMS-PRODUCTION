<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Form</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
* {
    padding: 0px;
    margin: 0px;
    box-sizing: border-box;
}

:root {
    --linear-grad: linear-gradient(to right, #141E30, #243B55);
    --grad-clr1: #141E30;
    --grad-clr2: #243B55;
}

body {
    height: 100vh;
    background: #f6f5f7;
    display: grid;
    place-content: center;
    font-family: 'Poppins', sans-serif;
}

.container {
    position: relative;
    width: 850px;
    height: 500px;
    background-color: #fff;
    box-shadow: 25px 30px 55px #5557;
    border-radius: 13px;
    overflow: hidden;
}

.form-container{
    position: absolute;
    width: 60%;
    height: 100%;
    padding: 0px 40px;
    transition: all 0.6s ease-in-out;
}

.log-in-container{
    z-index: 2;
}

form{
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0px 50px;
}

h1{
    color: var(--grad-ctr1);
}

.infield{
    position: relative;
    margin: 8px 0px;
    width: 100%;
}

.text-field{
    width: 100%;
    padding: 12px 15px;
    background-color: #f3f3f3;
    border: none;
    outline: none;
}

label{
    position: absolute;
    left: 50%;
    top: 100%;
    transform: translateX(-50%);
    width: 0%;
    height: 2px;
    background: var(--linear-grad);
    transition: 0.3s;
}

.text-field:focus ~ label{
    width: 100%;
}

.login-button{
    border-radius: 20px;
    border: 1px solid var(--grid-clr1);
    background: var( --grad-clr2);
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
}

.form-container .login-button{
    margin-top: 17px;
    transition: 80ms ease-in;
}

.form-container .login-button:hover{
    background: #004d66;
    color: #d4f5ff;
}

.overlay-container {
    position: absolute;
    top: 0;
    left: 60%;
    width: 40%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.6s ease-in-out;
    z-index: 9;
}

.overlay {
    position: relative;
    background: var(--linear-grad);
    color: brown;
    left: 0; 
    height: 100%;
    width: 100%; 
    transform: transform 0.6s ease-in-out;
}

.overlay-panel {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0px 40px;
    text-align: center;
    height: 100%;
    width: 100%; /* Change this to 100% to match the container width */
    transition: 0.6s ease-in-out;
}

.overlay-left {
    right: 0;
    transform: translate(-3%);
}

.overlay-panel h1 {
    color: white;
}

.image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px; /* Adjust this margin as needed */
}

.image-container img {
    max-width: 90%; /* Ensure the image doesn't exceed the container's width */
    max-height: 90%; /* Ensure the image doesn't exceed the container's height */
}



</style>
<body>

<div class="container" id="container">
    <div class="form-container log-in-container">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
           
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <h1>Login</h1>
                        <!-- Email Address -->
                        <div class="infield">
                            <x-text-input id="email" class="text-field" type="email" placeholder="Email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <label></label>
                        </div>

                        <!-- Password -->
                        <div class="infield">
                        <div class="mt-4">
                            <x-text-input id="password" class="text-field"
                                            type="password"
                                            placeholder="Password"
                                            name="password"
                                            required autocomplete="current-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <label></label>
                        </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="login-button">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
    </div>
    <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome!</h1>
                    <div class="image-container">
                        <img src="{{ asset('images/image/syemboy-gin-bulag.png') }}" alt="Your Image">
                    </div>
                </div>
            </div>
    </div>
</div>
</body>
</html>




