<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Obtener todos los productos
    public function index(Request $request)
    {  
        // Iniciar la consulta
        $query = Producto::query();

        // Filtrar por nombre (si se proporciona y no está vacío)
        if ($request->has('nombre') && !empty($request->input('nombre'))) {
            $query->where('nombre', 'like', '%' . $request->input('nombre') . '%');
        }

        // Filtrar por categoría (si se proporciona y no está vacío)
        if ($request->has('categoria') && !empty($request->input('categoria'))) {
            $query->where('categoria', $request->input('categoria'));
        }

        // Filtrar por precio mínimo (si se proporciona y no está vacío)
        if ($request->has('precio_min') && !empty($request->input('precio_min'))) {
            $query->where('precio', '>=', $request->input('precio_min'));
        }

        // Filtrar por precio máximo (si se proporciona y no está vacío)
        if ($request->has('precio_max') && !empty($request->input('precio_max'))) {
            $query->where('precio', '<=', $request->input('precio_max'));
        }

        // Obtener los resultados filtrados
        $productos = $query->get();

        // Devolver los resultados
        return response()->json($productos);
    }

    // Obtener un producto por su ID
    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto);
    }

    // Crear un nuevo producto
    public function store(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'imagen' => ['required', 'string', 'starts_with:/img/'],
            'nombre' => 'required|string|max:255',
            'categoria' => ['required', 'string', 'in:Frutas y Verduras,Alimentos Frescos,Suplementación,Líquidos y especias,Promociones'],
            'precio' => 'required|numeric|min:0',
        ]);

        // Definir la clave única para identificar el producto
        $claveUnica = [
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'precio' => $request->precio,
        ];

        // Crear o actualizar el producto
        $producto = Producto::updateOrCreate(
            $claveUnica, // Condición para buscar el producto
            [
                'imagen' => $request->imagen,
                'nombre' => $request->nombre,
                'categoria' => $request->categoria,
                'precio' => $request->precio,
            ]
        );

        // Devolver el producto creado o actualizado
        return response()->json($producto, 201); // 201: Creado exitosamente
    }

    // Actualizar un producto
    public function update(Request $request, $id)
    {
        // Buscar el producto por ID
        $producto = Producto::find($id);

        // Si el producto no existe, devolver un error 404
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Validación de los datos
        $request->validate([
            'imagen' => ['nullable', 'string', 'starts_with:/img/'],
            'nombre' => 'nullable|string|max:255',
            'categoria' => ['nullable', 'string', 'in:Frutas y Verduras,Alimentos Frescos,Suplementación,Líquidos y especias,Promociones'],
            'precio' => 'nullable|numeric|min:0',
        ]);

        // Actualizar solo los campos proporcionados
        $producto->update($request->only(['imagen', 'nombre', 'categoria', 'precio']));

        // Devolver el producto actualizado
        return response()->json($producto);
    }


    // Borrar un producto
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
