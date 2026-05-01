<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        // Obtener citas con relaciones para mostrar en la vista
        $citas = Cita::with(['paciente', 'odontologo', 'consultorio'])->get();
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        // Obtener listas para los select
        $pacientes = Paciente::all();
        $odontologos = Odontologo::all();
        $consultorios = Consultorio::all();
        return view('citas.create', compact('pacientes', 'odontologos', 'consultorios'));
    }

    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id',
            'id_odontologo' => 'required|exists:odontologos,id',
            'id_consultorio' => 'required|exists:consultorios,id',
            'fecha_hora' => 'required|date_format:Y-m-d\TH:i',
            'motivo' => 'nullable|string',
        ]);

        // Guardar la cita
        Cita::create($request->all());

        // Redirigir con mensaje de éxito
        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente');
    }

    public function show(Cita $cita)
    {
        // Cargar relaciones para la vista detallada
        $cita->load(['paciente', 'odontologo', 'consultorio']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        // Cargar listas para los select
        $pacientes = Paciente::all();
        $odontologos = Odontologo::all();
        $consultorios = Consultorio::all();
        return view('citas.edit', compact('cita', 'pacientes', 'odontologos', 'consultorios'));
    }

    public function update(Request $request, Cita $cita)
    {
        // Validar datos
        $request->validate([
            'id_paciente' => 'required|exists:pacientes,id',
            'id_odontologo' => 'required|exists:odontologos,id',
            'id_consultorio' => 'required|exists:consultorios,id',
            'fecha_hora' => 'required|date_format:Y-m-d\TH:i',
            'estado' => 'required|in:programada,realizada,cancelada',
            'motivo' => 'nullable|string',
        ]);

        // Actualizar la cita
        $cita->update($request->all());

        // Redirigir con mensaje de éxito
        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita)
    {
        // Eliminar la cita
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada exitosamente');
    }
}
