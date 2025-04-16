<?php 
namespace App\Http\Controllers;
class HomeController extends Controller {

public function index() {
    echo "is page index Home";
}

public function create() {
    echo "is page create Home";
}
public function store() {
    echo "is page store Home";
}
public function edit($id) {
    echo "is page edit Home";
}
public function update($id) {
    echo "is page update Home";
}
public function destroy($id) {
    echo "is page destroy Home";
}
}