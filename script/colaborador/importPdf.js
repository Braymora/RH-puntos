/*====================Input file====================*/
const pdfUpload = document.getElementById("pdf-upload");
const pdfPreview = document.getElementById("pdf-preview");

pdfUpload.addEventListener("change", function () {
  const file = pdfUpload.files[0];

  if (file && file.type === "application/pdf") {
    const reader = new FileReader();

    reader.onload = function (e) {
      pdfPreview.innerHTML = `<embed src="${e.target.result}" type="application/pdf" />`;
      pdfPreview.style.display = "block";
    };

    reader.readAsDataURL(file);
  } else {
    alert("Por favor, seleccione un archivo PDF válido.");
    pdfUpload.value = "";
    pdfPreview.style.display = "none";
  }
});
