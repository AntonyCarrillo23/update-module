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
        Schema::create('std_documents', function (Blueprint $table) {
            $table->id();
            $table->longText('asunto')->nullable();
            $table->string('anio',255)->nullable();
            $table->string('cCodificacion',255)->nullable();
            $table->date('fFecDocumento')->nullable();
            $table->longText('cNombreNuevo')->nullable();
            $table->text('nombre')->nullable();
            $table->string('institucion',255)->nullable();
            $table->string('representante_igp',255)->nullable();
            $table->string('representante_contraparte',255)->nullable();
            $table->string('coordinador_igp',255)->nullable();
            $table->string('coordinador_contraparte',255)->nullable();
            $table->text('objetivo')->nullable();
            $table->text('observacion')->nullable();
            $table->string('pdf_convenio',255)->nullable();
            $table->integer('adendas')->nullable();
            $table->integer('convenio_especifico')->nullable();
            $table->integer('idConvenioMarco')->nullable();
            $table->date('inicio')->nullable();
            $table->date('fin')->nullable();
            $table->date('firma')->nullable();
            $table->date('fecha_documento')->nullable();
            $table->integer('state')->nullable();
            $table->longText('observacion_situacion')->nullable();
            $table->string('estado',12)->nullable();
            $table->integer('enabled')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->date('fecha_ultima_adenda')->nullable();
            $table->decimal('monto_financiamiento',12)->nullable();
            $table->integer('serie_id')->nullable();
            $table->integer('subserie_id')->nullable();
            $table->integer('tipo_documental_id')->nullable();
            $table->integer('ordenamiento')->nullable();
            $table->integer('nro_correlativo')->nullable();
            $table->integer('nro_folios')->nullable();
            $table->string('nombre_archivo',255)->nullable();
            $table->integer('OrganoLinealId')->nullable();
            $table->integer('UnidadOrganicaId')->nullable();
            $table->integer('OrganoLinealIdVinculo')->nullable();
            $table->integer('UnidadOrganicaIdVinculo')->nullable();
            $table->string('gdrive_url',255)->nullable();
            $table->string('url_srcid',255)->nullable();
            $table->string('nro_expediente',50)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('std_documents');
    }
};
