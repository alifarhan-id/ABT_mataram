$(document).ready(function() {

    var table = $('.tablePerhitungan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "http://localhost/abt_mataram/public/perhitungan/list",
        columns: [
        { data: 'npwp', name: 'npwp' },
        { data: 'nama_wp', name: 'nama wp' },
        { data: 'masa', name: 'masa' },
        { data: 'tahun', name: 'tahun' },
        { data: 'pemakaian', name: 'pemakaian' },
        { data: 'tanggal_penetapan', name: 'tanggal_penetapan' },
        { data: 'abt', name: 'abt' },
        { data: 'pengurangan', name: 'pengurangan' },
        { data: 'abt_final', name: 'abt_final' },
        {data: 'action', name: 'action', orderable: false},
        ],
        order: [[0, 'desc']]
    })

    //get npwp
    function doGetWajibPajakByNpwp(npwp){
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });
      let _url = `http://localhost/abt_mataram/public/wajib-pajak/findByNpwp/${npwp}`
      $.ajax({
          url:_url,
          type:'GET',
          data:{npwp:npwp},
          dataType: 'json',
      }).done(function(res){

        if(res.length > 0){
            $('#npwpd-tambah').prop('readonly', true)
            $('#nama_wp-tambah').val(res[0].nama_wp)
            $('#jenis_usaha-tambah').val(res[0].jenis_usaha)
            $('#kelompok-tambah').val(res[0].kelompok)
            toastr.success(`Data Atas Nama ${res[0].nama_wp} DiTemukan`)
        }else{
            toastr.error('Data Tidak DiTemukan')
        }

      }).fail(function(err){
        toastr.error(`SERVER ERROR ${err}`)
      })

    }

    //parsing value to form
    $('#pemakaian_tambah').keyup(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let _url = `http://localhost/abt_mataram/public/perhitungan/hitung`
    let jsonData = {
        pemakaian : $('#pemakaian_tambah').val(),
        kelompok : $('#kelompok-tambah').val(),
    }

    $.ajax({
        url:_url,
        type:'POST',
        data:jsonData,
        dataType:'json'
    }).done(function(res){

        let data = res.original.results;
        $('#range_500-tambah').val(data.range_500)
        $('#range_1500-tambah').val(data.range_1500)
        $('#range_3000-tambah').val(data.range_3000)
        $('#range_5000-tambah').val(data.range_5000)
        $('#range_lebih_dari_5000-tambah').val(data.range_lebih_dari_5000)
        $('#hab-tambah').val(data.HAB)
        $('#fs-tambah').val(data.fs)
        $('#fp1-tambah').val(data.fp1)
        $('#fp2-tambah').val(data.fp2)
        $('#fp3-tambah').val(data.fp3)
        $('#fp4-tambah').val(data.fp4)
        $('#fp5-tambah').val(data.fp5)
        $('#npa1-tambah').val(data.NPA1)
        $('#npa2-tambah').val(data.NPA2)
        $('#npa3-tambah').val(data.NPA3)
        $('#npa4-tambah').val(data.NPA4)
        $('#npa5-tambah').val(data.NPA5)
        $('#total_npa-tambah').val(data.total_npa)
        $('#tarif_pajak-tambah').val(data.tarif_pajak)
        $('#nilai_progresive-tambah').val(data.nilai_pajak_progresive)
        $('#sangsi-tambah').val(0)
        $('#bunga-tambah').val(0)
        $('#pengurangan-tambah').val(data.pengurangan)
        $('#abt-tambah').val(data.abt)
        $('#abt_final-tambah').val(data.abt_final)


    }).fail(function(err){

    })

    })


    $('#btn-simpan').on('click', function(e){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let jsonData = {
            npwp                   :   $('#npwpd-tambah').val(),
            nama_wp                :   $('#nama_wp-tambah').val(),
            masa                   :   $('#masa-tambah').val(),
            tahun                  :   $('#tahun-pajak-tambah').val(),
            pemakaian              :   $('#pemakaian_tambah').val(),
            jenis_usaha            :   $('#jenis_usaha-tambah').val(),
            kelompok               :   $('#kelompok-tambah').val(),
            range_500              :   $('#range_500-tambah').val(),
            range_1500             :   $('#range_1500-tambah').val(),
            range_3000             :   $('#range_3000-tambah').val(),
            range_5000             :   $('#range_5000-tambah').val(),
            range_lebih_dari_5000  :   $('#range_lebih_dari_5000-tambah').val(),
            hab                    :   $('#hab-tambah').val(),
            fs                     :   $('#fs-tambah').val(),
            fp1                    :   $('#fp1-tambah').val(),
            fp2                    :   $('#fp2-tambah').val(),
            fp3                    :   $('#fp3-tambah').val(),
            fp4                    :   $('#fp4-tambah').val(),
            fp5                    :   $('#fp5-tambah').val(),
            npa1                   :   $('#npa1-tambah').val(),
            npa2                   :   $('#npa2-tambah').val(),
            npa3                   :   $('#npa3-tambah').val(),
            npa4                   :   $('#npa4-tambah').val(),
            npa5                   :   $('#npa5-tambah').val(),
            total_npa              :   $('#total_npa-tambah').val(),
            tarif_pajak            :   $('#tarif_pajak-tambah').val(),
            nilai_progresive       :   $('#nilai_progresive-tambah').val(),
            sangsi                 :   $('#sangsi-tambah').val(),
            bunga                  :   $('#bunga-tambah').val(),
            pengurangan            :   $('#pengurangan-tambah').val(),
            abt                    :   $('#abt-tambah').val(),
            abt_final              :   $('#abt_final-tambah').val()
        }

        $.ajax({
            url:'http://localhost/abt_mataram/public/perhitungan/create',
            type:'POST',
            data:jsonData,
            dataType:'json'
        }).done(function(res){
          if(res.success == true){
            $('#modal-create').modal('toggle');
            $("#form-tambah").trigger("reset");
            $('#npwpd-tambah').prop('readonly', false)
            toastr.success(`Data Berhasil Ditambahkan`)
          }else{
            toastr.error(`Gagal Menambahkan Data`)
          }
        }).fail(function(e){
            toastr.error(`Gagal Menambahkan Data message = ${e.responseJSON.message}`)
        })
    })

    $('#btn-reset').on('click', function(e){
        $("#form-tambah").trigger("reset");
    })


    $('#npwpd-query').on('click', function(e){
        let npwp = $('#npwpd-tambah').val()
       doGetWajibPajakByNpwp(npwp)
    })





});
