@extends('auth.layouts.auth')

@push('auth_content')

            <div class="card card-hidden">
                <div class="header text-center">{{ config('app.name') }}</div>
                <div class="content">
                    <div class="text-center">
                        <h5>- Log in With -</h5>
                        <a href="redirect/google" class="btn btn-fill btn-google">
                            <i class="fa fa-google"></i>Google
                        </a>
                    </div>
                </div>
            </div>
@endpush
