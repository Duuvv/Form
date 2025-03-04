<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscribirse</title>

  <!-- Bootstrap CSS from CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons from CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100">
      <!-- Sección izquierda: Mensaje de bienvenida -->
      <div class="col-md-6 bg-primary text-white rounded-start p-5 d-flex flex-column justify-content-center align-items-center">
        <h3 class="mb-4">¡Hola, Bienvenido!</h3>
        <p class="mb-4">Crea tu cuenta para acceder a todas las funciones del sitio.</p>
        <a href="login.php" class="btn btn-light">Ya tengo una cuenta</a>
      </div>

      <!-- Sección derecha: Formulario de Registro -->
      <div class="col-md-6 bg-white rounded-end p-5 shadow-lg">
        <form method="post" onsubmit="myaction.collect_data(event, 'signup')">
          <h2 class="mb-4">Crear Cuenta</h2>

          <!-- Nombre -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
            <input name="firstname" type="text" class="form-control p-3" placeholder="Nombre" required>
          </div>
          <div><small class="js-error js-error-firstname text-danger"></small></div>

          <!-- Apellido -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person-square"></i></span>
            <input name="lastname" type="text" class="form-control p-3" placeholder="Apellido" required>
          </div>
          <div><small class="js-error js-error-lastname text-danger"></small></div>

          <!-- Género -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
            <select class="form-select" name="gender" required>
              <option selected value="">--Seleccione Género--</option>
              <option value="Male">Masculino</option>
              <option value="Female">Femenino</option>
            </select>
          </div>
          <div><small class="js-error js-error-gender text-danger"></small></div>

          <!-- Email -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input name="email" type="text" class="form-control p-3" placeholder="Correo electrónico" required>
          </div>
          <div><small class="js-error js-error-email text-danger"></small></div>

          <!-- Número Telefónico -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input name="phone" type="text" class="form-control p-3" placeholder="Número Telefónico" required>
          </div>
          <div><small class="js-error js-error-phone text-danger"></small></div>

          <!-- Contraseña -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-key"></i></span>
            <input name="password" type="password" class="form-control p-3" placeholder="Contraseña" required>
          </div>
          <div><small class="js-error js-error-password text-danger"></small></div>

          <!-- Reingresar Contraseña -->
          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
            <input name="retype_password" type="password" class="form-control p-3" placeholder="Reingrese Contraseña" required>
          </div>
          <div><small class="js-error js-error-retype_password text-danger"></small></div>

          <!-- Aceptar términos y condiciones -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
            <label class="form-check-label" for="terms">
              Acepto los <a href="terms-and-conditions.html" target="_blank">Términos y Condiciones</a>
            </label>
          </div>

          <!-- Barra de progreso (inicialmente oculta) -->
          <div class="progress mt-3 d-none">
            <div class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
          </div>

          <!-- Botón de Registro -->
          <button class="mt-3 btn btn-primary w-100">Registrarse</button>
          <div class="m-2">
            ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
          </div>
        </form>
      </div>
    </div>
  </div>

<script>
  var myaction  = {
    collect_data: function(e, data_type) {
      e.preventDefault();
      e.stopPropagation();

      var inputs = document.querySelectorAll("form input, form select");
      let myform = new FormData();
      myform.append('data_type', data_type);

      for (var i = 0; i < inputs.length; i++) {
        myform.append(inputs[i].name, inputs[i].value);
      }

      myaction.send_data(myform);
    },

    send_data: function(form) {
      var ajax = new XMLHttpRequest();

      // Mostrar la barra de progreso
      var progressBarContainer = document.querySelector(".progress");
      var progressBar = document.querySelector(".progress-bar");
      progressBarContainer.classList.remove("d-none");

      // Reiniciar la barra de progreso
      progressBar.style.width = "0%";
      progressBar.innerHTML = "Trabajando... 0%";

      // Calcular el progreso por cada campo
      var totalFields = 7;  // Nombre, Apellido, Género, Email, Teléfono, Contraseña, Reingresar Contraseña
      var progressPerField = 100 / totalFields;

      // Función para actualizar la barra de progreso
      var updateProgress = function(percentage) {
        progressBar.style.width = percentage + "%";
        progressBar.innerHTML = Math.round(percentage) + "%";
      };

      // Escuchar el evento 'input' para ir aumentando la barra conforme el usuario complete los campos
      var fieldCount = 0;
      var inputs = document.querySelectorAll("form input, form select");
      inputs.forEach(function(input) {
        input.addEventListener('input', function() {
          fieldCount++;
          var progress = fieldCount * progressPerField;
          updateProgress(progress);  // Actualizar la barra
        });
      });

      // Manejar el resultado de la solicitud AJAX
      ajax.addEventListener('readystatechange', function() {
        if (ajax.readyState == 4) {
          if (ajax.status == 200) {
            // Todo bien, manejar el resultado
            myaction.handle_result(ajax.responseText);
          } else {
            alert("Ocurrió un error");
          }
        }
      });

      ajax.open('post', 'ajax.php', true);
      ajax.send(form);
    },

    handle_result: function(result) {
      var obj = JSON.parse(result);
      if (obj.success) {
        alert("Perfil creado con éxito");
        // Ocultar la barra de progreso al finalizar
        document.querySelector(".progress").classList.add("d-none");
        window.location.href = 'login.php';
      } else {
        // Traducir mensajes de error específicos al español
        for (key in obj.errors) {
          if(obj.errors[key].indexOf("Passwords don't match") !== -1) {
            obj.errors[key] = "Las contraseñas no coinciden";
          }
          if(obj.errors[key].indexOf("at least 8 characters") !== -1) {
            obj.errors[key] = "La contraseña debe tener al menos 8 caracteres";
          }
          document.querySelector(".js-error-" + key).innerHTML = obj.errors[key];
        }
        // Ocultar la barra de progreso en caso de error
        document.querySelector(".progress").classList.add("d-none");
      }
    }
  };
</script>

<!-- Bootstrap JS (por si es necesario) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
