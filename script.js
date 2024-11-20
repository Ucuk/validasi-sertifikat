document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var inputFile = document.getElementById('sertifikatInput').files[0];
    if (!inputFile) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Tidak ada file yang dipilih!',
        });
        return;
    }

    var fileReader = new FileReader();

    fileReader.onload = function(e) {
        var arrayBuffer = e.target.result;
        var spark = new SparkMD5.ArrayBuffer();
        
        spark.append(arrayBuffer);
        var hashHex = spark.end();

        document.getElementById('hashResult').textContent = hashHex;

        // Kirim hash ke server untuk validasi
        var formData = new FormData();
        formData.append('hash', hashHex);

        fetch('validate.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'valid') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sertifikat Valid!',
                    text: data.message,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Sertifikat Tidak Valid',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat memvalidasi sertifikat.',
            });
        });
    };

    // Handle tampilan gambar
    var imgElement = document.getElementById('sertifikatImg');
    if (inputFile.type.startsWith("image/")) {
        var imgReader = new FileReader();
        imgReader.onload = function(e) {
            imgElement.src = e.target.result;
        };
        imgReader.readAsDataURL(inputFile);
    } else {
        imgElement.src = "sert2.jpg";
    }

    fileReader.readAsArrayBuffer(inputFile);
});