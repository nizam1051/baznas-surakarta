<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.stisla.head')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            @include('admin.stisla.navbar')
            @include('admin.stisla.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Form Permohonan Bantuan</h1>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <!-- <div class="card-header">
                                        <h4>Add Post</h4>
                                    </div> -->
                                    <div class="card-body">
                                        <form action="{{ route('store.request') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis
                                                    Bantuan</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <select type="select" class="form-control" name="category">
                                                        <option value="1">Kemanusiaan</option>
                                                        <option value="2">Pendidikan</option>
                                                        <option value="3">Kesehatan</option>
                                                        <option value="4">Dakwah dan Advokasi</option>
                                                        <option value="5">Ekonomi Produktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIK</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <input name="nik" type="text" class="form-control" name="title">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama
                                                    Lengkap</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <input type="text" class="form-control" name="name">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat
                                                    Tanggal Lahir</label>
                                                <div class="col-sm-6 col-md-3">
                                                    <input type="text" class="form-control" name="birthplace">
                                                </div>
                                                <div class="col-sm-6 col-md-3">
                                                    <input type="date" class="form-control" name="birthdate">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"
                                                    for="alamat">Alamat</label>
                                                <div class="col-md-9">
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 col-md-4">
                                                            <select class="form-control form-select bg-white"
                                                                name="provinsi" id="select-province">
                                                                <option value="">Pilih Provinsi</option>
                                                                @foreach ($data as $provinsi)
                                                                <option value="{{ $provinsi->id }}">
                                                                    {{ $provinsi->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6 col-md-4">
                                                            <select class="form-control form-select bg-white"
                                                                name="kabupaten" id="select-district">
                                                                <option class="form-option" value="" disabled selected>
                                                                    Pilih
                                                                    Kota/Kabupaten</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-6 col-md-4">
                                                            <select class="form-control form-select bg-white"
                                                                name="kecamatan" id="select-regency"
                                                                onchange="pilihKec(this.value)">
                                                                <option class="form-option" value="" disabled selected>
                                                                    Pilih
                                                                    Kecamatan</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6 col-md-4">
                                                            <select class="form-control form-select bg-white"
                                                                name="kelurahan" id="select-village">
                                                                <option class="form-option" value="" disabled selected>
                                                                    Pilih
                                                                    Kelurahan/Desa</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Agama</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <select type="select" class="form-control" name="religion">
                                                        <option value="Islam">Islam</option>
                                                        <option value="Katholik">Katholik</option>
                                                        <option value="Protestan">Protestan</option>
                                                        <option value="Hindu">Hindu</option>
                                                        <option value="Budha">Budha</option>
                                                        <option value="Konghucu">Konghucu</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pekerjaan</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <input type="text" class="form-control" name="job">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">No
                                                    Telp</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <input type="text" class="form-control" name="phone_number">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keterangan</label>
                                                <div class="col-sm-12 col-md-7">
                                                    <textarea style="height: 150px;" name="description"
                                                        class="form-control summernote-simple"></textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3"></label>
                                                <div class="col-sm-12 col-md-6">
                                                    <h3>Persyaratan Pendidikan</h3>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label for="surat_permohonan"
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">Surat
                                                    Permohonan</label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="surat_permohonan"
                                                        id="surat_permohonan">
                                                    <label class="custom-file-label" for="surat_permohonan">Choose
                                                        file</label>
                                                    <div class="mt-2">Template surat permohonan bisa download <a
                                                            href="/assets/file/Format%20Surat%20Permohonan%20Bantuan%20Perorangan.docx"
                                                            class="font-weight-bold text-primary">Di
                                                            sini</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">Scan
                                                    KTP</label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="ktp"
                                                        id="customFile">
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>

                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">Scan
                                                    KK</label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="kk"
                                                        id="customFile">
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>

                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">SKTM
                                                    atau Gakin
                                                </label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="sktm"
                                                        id="customFile">
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>

                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">Suket
                                                    Permohonan Bantuan<br> ke Baznas dari kelurahan
                                                </label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="suket"
                                                        id="customFile">
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label mr-3 text-md-right col-12 col-md-3 col-lg-3">Tagihan
                                                    dari Sekolah
                                                </label>
                                                <div class="col-sm-12 col-md-6">
                                                    <input type="file" class="custom-file-input" name="tagihan"
                                                        id="customFile">
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>
                                                </div>
                                            </div> -->
                                            <div class="form-group row mb-4">
                                                <label
                                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                                <div class="col-sm-12 col-md-7">
                                                    <button type="submit" class="btn btn-primary">Tambah
                                                        Permohonan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- This is where your code ends -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                @include('admin.stisla.footer')
            </footer>
        </div>
    </div>

    @include('admin.stisla.script')

    <script type="text/javascript">
    var rupiah = document.getElementById('rupiah');
    rupiah.addEventListener('keyup', function(e) {
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        rupiah.value = formatRupiah(this.value, 'Rp. ');
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
    </script>
    <script>
    CKEDITOR.replace('content');
    </script>
    <!-- 
    <script>
    let kec = [
        "Pasar Kliwon",
        "Jebres",
        "Banjarsari",
        "Laweyan",
        "Serengan",
    ]
    let kelurahan = [
        ['Banyuanyar', 'Banjarsari', 'Gilingan', 'Joglo', 'Kadipiro', 'Keprabon', 'Kestalan', 'Ketelan', 'Manahan',
            'Mangkubumen', 'Nusukan', 'Punggawan', 'Setabelan', 'Sumber', 'Timuran'
        ],
        ['Gandekan', 'Jagalan', 'Jebres', 'Kepatihan Kulon', 'Kepatihan Wetan', 'Mojosongo', 'Pucang Sawit',
            'Purwodiningratan', 'Sewu', 'Sudiroprajan', 'Tegalharjo'
        ],
        ['Bumi', 'Jajar', 'Karangasem', 'Kerten', 'Laweyan', 'Pajang', 'Panularan', 'Penumping', 'Purwosari',
            'Sondakan', 'Sriwedari'
        ],
        ['Baluwarti', 'Gajahan', 'Joyosuran', 'Kampung Baru', 'Kauman', 'Kedung Lumbu', 'Mojo', 'Pasar Kliwon',
            'Sangkrah', 'Semanggi'
        ],
        ['Danukusuman', 'Jayengan', 'Joyotakan', 'Kemlayan', 'Kratonan', 'Serengan', 'Tipes']
    ]
    </script>

    <script>
    for (i = 0; i < kec.length; i++) {
        $('#kecamatan').append('<option value="' + kec[i] + '">' + kec[i] + '</option>');
    }

    function pilihKec(value) {
        index = kec.indexOf(value)
        $("#kelurahan option").remove();
        for (i = 0; i < kelurahan[index].length; i++) {
            $('#kelurahan').append('<option value="' + kelurahan[index][i] + '">' + kelurahan[index][i] + '</option>');
            console.log(kelurahan[index][i])
        }
    }
    </script> -->

    <!-- Address -->
    <script>
    // Pilih Provinsi
    $(document).on('change', '#select-province', function() {
        let province_id = $(this).val();
        // ! Remove Html Below
        $('#select-district').html('<option value="">Pilih Kota/Kabupaten</option>')
        $('#select-regency').html('<option value="">Pilih Kecamatan</option>')
        $('#select-village').html('<option value="">Pilih Kelurahan/Desa</option>')

        $(document).ready(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/get-district",
                method: 'POST',
                data: {
                    province_id: province_id
                },
                success: function(response) {
                    let districs = response;
                    let option = ['<option value="">Pilih Kota/Kabupaten</option>']
                    districs.forEach(element => {
                        option.push('<option value=' + element['id'] + '>' +
                            element['name'] + '</option>')
                    });
                    $('#select-district').html(option)
                }
            })
        });
    })

    // Pilih Kabupaten/Kota
    $(document).on('change', '#select-district', function() {
        let district_id = $(this).val();
        let province_id = $('#select-province').val();

        // ! Remove Html Below
        $('#select-regency').html('<option value="">Pilih Kecamatan</option>')
        $('#select-village').html('<option value="">Pilih Kelurahan/Desa</option>')
        $(document).ready(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/get-regency",
                method: 'POST',
                data: {
                    district_id: district_id,
                    province_id: province_id
                },
                success: function(response) {
                    let districs = response;
                    let option = ['<option value="">Pilih Kecamatan</option>']
                    districs.forEach(element => {
                        option.push('<option value=' + element['id'] + '>' +
                            element['name'] + '</option>')
                    });
                    $('#select-regency').html(option)


                }
            })
        });
    })

    // Pilih Kabupaten/Kota
    $(document).on('change', '#select-regency', function() {
        let regency_id = $(this).val();
        let district_id = $('#select-district').val();
        let province_id = $('#select-province').val();

        // ! Remove Html Below
        $('#select-village').html('<option value="">Pilih Kelurahan/Desa</option>')
        $(document).ready(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/get-village",
                method: 'POST',
                data: {
                    regency_id: regency_id,
                    district_id: district_id,
                    province_id: province_id
                },
                success: function(response) {
                    let districs = response;
                    let option = ['<option value="">Pilih Kelurahan/Desa</option>']
                    districs.forEach(element => {
                        option.push('<option value=' + element['id'] + '>' +
                            element['name'] + '</option>')
                    });
                    $('#select-village').html(option)

                }
            })
        });
    })
    </script>

</body>

</html>