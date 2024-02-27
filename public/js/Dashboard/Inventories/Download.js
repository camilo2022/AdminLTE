function DownloadInventory() {
    Swal.fire({
        title: '¿Desea descargar el archivo de inventarios?',
        text: 'El archivo de inventarios se procesara y descargara.',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#DD6B55',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Si, descargar!',
        cancelButtonText: 'No, cancelar!'
    }).then((result) => {
        if (result.value) {
            toastr.info('Por favor espere un momento a que se procese, genere y descargue el archivo.');
            document.DownloadInventories.submit();
        } else {
            toastr.info('El archivo de inventarios no fue descargado.')
        }
    });
}
