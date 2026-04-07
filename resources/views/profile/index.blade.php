@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('content')

<div class="p-6 bg-gray-100 min-h-screen">

    @include('profile.partials.profile-form')

</div>

@endsection