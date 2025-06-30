@props(['size' => 'w-10 h-10'])

<img id="user-avatar"
     src="{{ auth()->user()->avatar 
             ? asset('storage/' . auth()->user()->avatar) . '?t=' . now()->timestamp 
             : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=B89F83&color=fff' }}"
     alt="User avatar"
     class="{{ $size }} rounded-full">