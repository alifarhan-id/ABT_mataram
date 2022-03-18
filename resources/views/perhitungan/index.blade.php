@extends('layouts.main')

@section('header-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Perhitungan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Perhitungan</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection
@section('content')
<div class="row">
    <!-- /.col -->
    <div class="col-md-12">
      <div class="card card-outline card-warning">
        <div class="card-header text-center">
          <h3 class="card-title">Perhitungan Dan Penetapan</h3>
          <div class="card-tools mr-2">
            <button type="button" class="btn btn-block btn-outline-warning btn-sm" data-toggle="modal" data-target="#modal-create">Tambah Data Perhitungan</button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div>
            <table id="tablePerhitungan" class="display tablePerhitungan" style="width:100%">
                <thead>
                    <tr>
                        <th>NPWPD</th>
                        <th>NAMA</th>
                        <th>MASA</th>
                        <th>TAHUN</th>
                        <th>PEMAKAIAN (m3)</th>
                        <th>TANGGAL_PENETAPAN</th>
                        <th>ABT</th>
                        <th>PENGURANGAN</th>
                        <th>ABT FINAL</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>

</div>

{{-- modal create --}}
<div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Perhitungan Dan Penetapan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        <form class="form-horizontal" id="form-tambah">
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                    <div class="card-body">
                      <div class="form-group row">
                        <label for="npwpd-tambah" class="col-sm-2 col-form-label">NPWPD :</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <!-- /btn-group -->
                                <input type="text" class="form-control" name="npwpd-tambah" id="npwpd-tambah">
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-danger" id="npwpd-query">GO</button>
                                </div>
                              </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="nama_wp-tambah" class="col-sm-2 col-form-label">NAMA :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="nama_wp-tambah" id="nama_wp-tambah" placeholder="Nama WP" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="masa_pajak-tambah" class="col-sm-2 col-form-label">MASA :</label>
                        <div class="col-sm-10">
                                <select class="form-control select2" id="masa-tambah" style="width: 100%;">
                                  <option selected="selected">Pilih Masa Pajak</option>
                                  <option value="1">January</option>
                                  <option value="2">February</option>
                                  <option value="3">Maret</option>
                                  <option value="4">April</option>
                                  <option value="5">Mei</option>
                                  <option value="6">juni</option>
                                  <option value="7">juli</option>
                                  <option value="8">Agustus</option>
                                  <option value="9">September</option>
                                  <option value="10">Oktober</option>
                                  <option value="11">November</option>
                                  <option value="12">Desember</option>
                                </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="tahun-pajak-tambah" class="col-sm-2 col-form-label">TAHUN :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="tahun_pajak-tambah" id="tahun-pajak-tambah" placeholder="Tahun">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="pemakaian_tambah" class="col-sm-2 col-form-label">PEMAKAIAN :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="pemakaian_tambah" id="pemakaian_tambah" placeholder="Masukan Pemakaian (m3)">
                        </div>
                      </div>
                    </div>

            </div>
            <div class="col-md-2"></div>
          </div>
          <hr style="border:1px solid green;">
          <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-8">
                <div class="form-group row">
                    <label for="abt_final-tambah" class="col-sm-2 col-form-label">ABT Final :</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="abt_final-tambah" id="abt_final-tambah" readonly>
                    </div>
                </div>
              </div>
              <div class="col-md-2"></div>
          </div>
          <hr style="border:1px solid green;">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="jenis_usaha-tambah" class="col-sm-3 col-form-label">Jenis Usaha :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="jenis_usaha-tambah" id="jenis_usaha-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kelompok-tambah" class="col-sm-3 col-form-label">Kelompok :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="kelompok-tambah" id="kelompok-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="range_500-tambah" class="col-sm-3 col-form-label">500 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="range_500_tambah" id="range_500-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="range_1500-tambah" class="col-sm-3 col-form-label">1500 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="range_1500-tambah" id="range_1500-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="range_3000-tambah" class="col-sm-3 col-form-label">3000 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="range_3000-tambah" id="range_3000-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="range_5000-tambah" class="col-sm-3 col-form-label">5000 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="range_5000-tambah" id="range_5000-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for=">range_lebih_dari_5000-tambah" class="col-sm-3 col-form-label">>5000 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name=">range_lebih_dari_5000-tambah" id="range_lebih_dari_5000-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hab-tambah" class="col-sm-3 col-form-label">HAB :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="hab-tambah" id="hab-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fs-tambah" class="col-sm-3 col-form-label">f(S) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fs-tambah" id="fs-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fp1-tambah" class="col-sm-3 col-form-label">f(P1) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fp1-tambah" id="fp1-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fp2-tambah" class="col-sm-3 col-form-label">f(P2) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fp2-tambah" id="fp2-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fp3-tambah" class="col-sm-3 col-form-label">f(P3) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fp3-tambah" id="fp3-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fp4-tambah" class="col-sm-3 col-form-label">f(P4) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fp4-tambah" id="fp4-tambah" readonly>
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="fp5-tambah" class="col-sm-3 col-form-label">f(P5) :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="fp5-tambah" id="fp5-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npa1-tambah" class="col-sm-3 col-form-label">NPA 1 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="npa1-tambah" id="npa1-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npa2-tambah" class="col-sm-3 col-form-label">NPA 2 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="npa2-tambah" id="npa2-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npa3-tambah" class="col-sm-3 col-form-label">NPA 3 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="npa3-tambah" id="npa3-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npa4-tambah" class="col-sm-3 col-form-label">NPA 4 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="npa4-tambah" id="npa4-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npa5-tambah" class="col-sm-3 col-form-label">NPA 5 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="npa5-tambah" id="npa5-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total_npa-tambah" class="col-sm-3 col-form-label">NPA :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="total_npa-tambah" id="total_npa-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tarif_pajak-tambah" class="col-sm-3 col-form-label">Tarfi Pajak 20% :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="tarif_pajak-tambah" id="tarif_pajak-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nilai_progresive-tambah" class="col-sm-3 col-form-label">Per m3 :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="nilai_progresive-tambah" id="nilai_progresive-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sangsi-tambah" class="col-sm-3 col-form-label">Sangsi :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="sangsi-tambah" id="sangsi-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bunga-tambah" class="col-sm-3 col-form-label">Bunga :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="bunga-tambah" id="bunga-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pengurangan-tambah" class="col-sm-3 col-form-label">Pengurangan :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="pengurangan-tambah" id="pengurangan-tambah" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="abt-tambah" class="col-sm-3 col-form-label">ABT :</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="abt-tambah" id="abt-tambah" readonly>
                    </div>
                </div>

            </div>
          </div>
        </form>
        </div>
        <div class="modal-footer justify-content-between">
           <button type="button" class="btn btn-danger" id="btn-reset">reset</button>
          <button type="button" class="btn btn-warning" id="btn-simpan">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script src="{{ url('js/perhitungan.js')}}"></script>
@endsection
