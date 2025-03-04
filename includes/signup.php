<?php

    //validate firstname
    if(empty($_POST['firstname']))
    {
        $info['errors']['firstname'] = "A first name is required";
    }
    else if(!preg_match("/^[\p{L}]+$/u", $_POST['firstname']))
    {
        $info['errors']['firstname'] = "First name can't have special characters, spaces, or numbers";
    }

    //validate lastname
    if(empty($_POST['lastname']))
    {
        $info['errors']['lastname'] = "A last name is required";
    }
    else if(!preg_match("/^[\p{L}]+$/u", $_POST['lastname']))
    {
        $info['errors']['lastname'] = "Last name can't have special characters, spaces, or numbers";
    }

    //validate email
    if(empty($_POST['email']))
    {
        $info['errors']['email'] = "An email is required";
    }
    else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
    {
        $info['errors']['email'] = "Email is not valid";
    }

    //validate gender
    $genders = ['Male','Female'];
    if(empty($_POST['gender']))
    {
        $info['errors']['gender'] = "A gender is required";
    }
    else if(!in_array($_POST['gender'], $genders))
    {
        $info['errors']['gender'] = "Gender is not valid";
    }

    //validate phone
    if(empty($_POST['phone']))
    {
        $info['errors']['phone'] = "A phone number is required";
    }
    else if(!preg_match("/^[0-9\-\s\(\)+]*$/", $_POST['phone']))
    {
        // Ajusta el patrón según el formato que desees permitir
        $info['errors']['phone'] = "Phone number can only contain digits, spaces, parentheses, and + or - signs.";
    }

    //validate password
    if(empty($_POST['password']))
    {
        $info['errors']['password'] = "A password is required";
    }
    else if($_POST['password'] !== $_POST['retype_password'])
    {
        $info['errors']['password'] = "Passwords don't match";
    }
    else if(strlen($_POST['password']) < 8)
    {
        $info['errors']['password'] = "Password must be at least 8 characters long";
    }


    // Si no hay errores, procedemos a guardar en la BD
    if(empty($info['errors']))
    {
        // Prepara array para bindear parámetros en la consulta
        $arr = [];
        $arr['firstname'] = $_POST['firstname'];
        $arr['lastname']  = $_POST['lastname'];
        $arr['email']     = $_POST['email'];
        $arr['phone']     = $_POST['phone'];
        $arr['gender']    = $_POST['gender'];
        $arr['password']  = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $arr['date']      = date("Y-m-d H:i:s");

        // IMPORTANTE: asegúrate de que en tu tabla users exista la columna phone
        db_query("INSERT INTO users (firstname, lastname, email, phone, gender, password, date) 
                  VALUES (:firstname, :lastname, :email, :phone, :gender, :password, :date)", 
                  $arr);

        $info['success'] = true;
    }