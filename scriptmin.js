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

        // Tampilkan hash (opsional)
        document.getElementById('hashResult').textContent = hashHex;

        // Kirim hash ke server untuk validasi dan penyimpanan
        var formData = new FormData();
        formData.append('hash', hashHex);

        fetch('validatemin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Respon jaringan tidak valid');
            }
            return response.json();
        })
        .then(data => {
            // Tangani respons dari server
            if (data.status === 'valid') {
                // Pilih icon berdasarkan action
                let icon = data.action === 'new' ? 'success' : 'info';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Validasi Sertifikat',
                    text: data.message,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan saat memproses sertifikat.',
            });
        });

        // Handle tampilan gambar
        var imgElement = document.getElementById('sertifikatImg');
        if (inputFile.type.startsWith("image/")) {
            var imgReader = new FileReader();
            imgReader.onload = function(e) {
                imgElement.src = e.target.result;
                imgElement.style.display = 'block';
            };
            imgReader.readAsDataURL(inputFile);
        } else {
            imgElement.src = "sert2.jpg";
            imgElement.style.display = 'block';
        }
    };

    fileReader.readAsArrayBuffer(inputFile);
});