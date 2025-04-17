<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('provinsi')->nullable(); // enum
            $table->string('kota')->nullable(); // enum
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('bidang')->nullable(); // enum
            $table->string('sub_bidang')->nullable(); // enum
            $table->string('tarif_sppd')->nullable(); // enum
            $table->string('koefisien_lembur')->nullable(); // enum
            $table->boolean('is_shifting')->nullable();
            $table->boolean('is_magang')->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            $table->enum('religion', ['ISLAM', 'PROTESTAN', 'KATOLIK', 'BUDDHA', 'HINDU', 'KONGHUCU', 'LAINNYA'])->nullable();
            $table->date('birthday')->nullable();
            $table->string('birth_place')->nullable();
            $table->enum('marital_status', ['lajang', 'menikah', 'janda/duda'])->nullable();
            $table->date('wedding_date')->nullable();
            $table->integer('child')->nullable();
            $table->string('mother_name')->nullable();
            $table->enum('blood_type', ['A', 'B', 'O', 'AB'])->nullable();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->enum('ukuran_baju', ['S', 'M', 'L', 'XL'])->nullable(); // enum
            $table->string('bank')->nullable(); // enum
            $table->string('nama_rekening')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('ktp')->nullable();
            $table->string('npwp')->nullable();
            $table->string('kk')->nullable();
            $table->string('bpjs')->nullable();
            $table->string('nominal_bpjs')->nullable();
            $table->date('bpjs_active_date')->nullable();
            $table->string('dlpk')->nullable();
            $table->string('cif')->nullable();
            $table->string('nominal_dlpk')->nullable();
            $table->date('dlpk_active_date')->nullable();
            $table->string('pendidikan_terakhir')->nullable(); // enum
            $table->string('jurusan')->nullable();
            $table->string('gelar')->nullable();
            $table->string('status_kontrak')->nullable(); // enum
            $table->string('nomor_kontrak')->nullable();
            $table->date('tanggal_penerimaan')->nullable();
            $table->date('tanggal_aktif_bekerja')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};