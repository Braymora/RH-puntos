/*====================Modal Import PDF====================*/

// Función para mostrar el modal
function showModalPdfAnexo() {
  document.getElementById("myModalPdf_anexo").classList.add("show");
}

// Función para cerrar el modal
function closeModalPdf() {
  document.getElementById("myModalPdf_anexo").classList.remove("show");
}



/*====================Input file====================*/
const pdfUpload__anexo = document.getElementById("pdf-upload__anexo");
const pdfPreview__anexo = document.getElementById("pdf-preview__anexo");

pdfUpload__anexo.addEventListener("change", function () {
  const file = pdfUpload__anexo.files[0];

  if (file && file.type === "application/pdf") {
    const reader = new FileReader();

    reader.onload = function (e) {
      pdfPreview__anexo.innerHTML = `<embed src="${e.target.result}" type="application/pdf" />`;
      pdfPreview__anexo.style.display = "block";
    };

    reader.readAsDataURL(file);
  } else {
    alert("Por favor, seleccione un archivo PDF válido.");
    pdfUpload__anexo.value = "";
    pdfPreview__anexo.style.display = "none";
  }
});
