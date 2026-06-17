<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    //GET
    public function index()
    {
        return response()->json(Question::all(), 200);
    }

    //POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        $question = Question::create($validated);

        return response()->json($question, 201);
    }

    //GET
    public function show(Question $question)
    {
        return response()->json($question, 200);
    }

    //PATCH
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'text' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        $question->update($validated);

        return response()->json($question, 200);
    }

    //DELETE
    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully'], 200);
    }
}
