/**
 * Controlador AJAX - Formulario de Aplicación de Usuario
 */

document.addEventListener('DOMContentLoaded', () => {

  const form        = document.getElementById('formUsuario');
  const btnProcesar = document.getElementById('btnProcesar');

  // Validación visual en tiempo real
  ['nombreCompleto', 'edad', 'sueldoPretendido'].forEach(id => {
    document.getElementById(id).addEventListener('input', function () {
      if (form.classList.contains('was-validated')) validateField(this);
    });
  });

  function validateField(input) {
    if (input.checkValidity()) {
      input.classList.replace('is-invalid', 'is-valid') || input.classList.add('is-valid');
    } else {
      input.classList.replace('is-valid', 'is-invalid') || input.classList.add('is-invalid');
    }
  }

  
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    form.classList.add('was-validated');

    
    const sueldoInput = document.getElementById('sueldoPretendido');
    const sueldoError = document.getElementById('sueldoError');
    const sueldoValido = sueldoInput.value !== '' && parseFloat(sueldoInput.value) >= 0;

    if (!sueldoValido) {
      sueldoError.textContent = 'Ingrese un sueldo válido mayor o igual a 0.';
      sueldoInput.classList.add('is-invalid');
      sueldoInput.classList.remove('is-valid');
    } else {
      sueldoError.textContent = '';
      sueldoInput.classList.remove('is-invalid');
      sueldoInput.classList.add('is-valid');
    }

    
    let formValido = true;
    form.querySelectorAll('input').forEach(c => {
      validateField(c);
      if (!c.checkValidity()) formValido = false;
    });

    if (!formValido || !sueldoValido) return;

    
    const datos = new FormData();
    datos.append('nombre', document.getElementById('nombreCompleto').value.trim());
    datos.append('edad',   document.getElementById('edad').value);
    datos.append('sueldo', parseFloat(sueldoInput.value).toFixed(2));

    
    btnProcesar.disabled = true;
    btnProcesar.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
      Procesando...
    `;

    
    fetch('../modelo/usuario.php', {
      method: 'POST',
      body: datos
    })
    .then(res => {
      if (!res.ok) throw new Error(`Error HTTP: ${res.status}`);
      return res.json();
    })
    .then(json => {
      if (json.status === true) {
        Swal.fire({
          icon: 'success',
          title: '¡Aplicación Procesada!',
          text: json.mensaje,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#FF7A2A',
          background: '#0B1020',
          color: '#e0e7ff',
          iconColor: '#34d399'
        });
        resetForm();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Solicitud Rechazada',
          text: json.mensaje,
          confirmButtonText: 'Corregir',
          confirmButtonColor: '#4A6ED1',
          background: '#0B1020',
          color: '#e0e7ff',
          iconColor: '#f87171'
        });
      }
    })
    .catch(err => {
      Swal.fire({
        icon: 'error',
        title: 'Error de Conexión',
        text: 'No se pudo conectar con el servidor. Intente nuevamente.',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#4A6ED1',
        background: '#0B1020',
        color: '#e0e7ff'
      });
      console.error(err);
    })
    .finally(() => {
      btnProcesar.disabled = false;
      btnProcesar.innerHTML = `
        <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 11 12 14 22 4"/>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
        Procesar Aplicación
      `;
    });
  });

  function resetForm() {
    form.reset();
    form.classList.remove('was-validated');
    form.querySelectorAll('input').forEach(c => c.classList.remove('is-valid', 'is-invalid'));
    document.getElementById('sueldoError').textContent = '';
  }

});
