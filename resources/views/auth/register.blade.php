@extends('layouts.head')

@include('partials.navbar')

@include('partials.auth.register_form', ['admin_page_version' => false])
