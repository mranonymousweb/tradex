@extends('errors::minimal')

@section('title', __('general.error_forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'general.error_forbidden'))
