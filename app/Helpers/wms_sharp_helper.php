<?php 

function get_button_view($url = '#', $label = "View"){
  return '<a class="waves-effect waves-light btn btn-small indigo darken-4 btn-view" href="' . $url . '">' . $label . '</a>';
}

function get_button_print($url = '#', $label = "Print"){
  return '<a class="waves-effect waves-light btn btn-small green darken-4 btn-print mt-1" href="' . $url . '">' . $label . '</a>';
}

function get_button_return($url = '#', $label = "Send Back"){
  return '<a class="waves-effect waves-light btn btn-small orange darken-4 btn-return" href="' . $url . '">' . $label . '</a>';
}

function get_button_edit($url = '#', $label = "Edit"){
  return '<a class="waves-effect  waves-light btn-small amber darken-4 btn-edit" href="' . $url . '">' . $label . '</a>';
}

function get_button_delete($label = "Delete"){
  return '<a class="waves-effect waves-light red darken-4 btn-small btn-delete" >' . $label . '</a>';
}

function get_button_save($label = "Save"){
  return '<button type="submit" class="waves-effect waves-light indigo btn-small btn-save mt-2 mr-1 mb-1">' . $label . '</button>';
}

function get_button_cancel($url = '#', $label = "Cancel"){
  return '<a class="waves-effect btn-flat mt-2 mb-1" href="' . $url . '">' . $label . '</a>';
}

function get_button_submit($label = "Submit"){
  return '<a class="waves-effect waves-light indigo btn-small btn btn-save mr-2">' . $label . '</a>';
}

function get_button_clear($label = "Clear"){
  return '<a class="waves-effect waves-light red darken-4 btn btn-delete mt-2 mr-2 mb-1" >' . $label . '</a>';
}

function get_button_update($label = "Update"){
  return '<a class="waves-effect waves-light indigo btn btn-delete mt-2 mr-1 mb-1" >' . $label . '</a>';
}

function get_button_load($label = "Load"){
  return '<a class="waves-effect waves-light indigo btn btn-delete mt-2 mr-1 mb-1" >' . $label . '</a>';
}