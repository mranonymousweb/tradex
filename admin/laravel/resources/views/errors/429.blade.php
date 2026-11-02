@extends('errors::minimal')

@section('title', __('general.error_too_many_requests'))
@section('code', '429')
@section('message', __('general.error_too_many_requests'))
