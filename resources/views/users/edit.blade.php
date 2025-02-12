

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{route('users.update',$users->id)}}" method="post">
                        @csrf
                        <div>
                            <div class="container flex justify-between items-center">
                                <label for="" class="text-xl font-medium">Update Form</label>

                                <div class="create  ">
                                    <a class=" px-4 py-2 flex items-center" href="{{route('users.index')}}"><i
                                            class="fa-solid fa-angle-left text-2xl p-3"></i> Back</a>
                                </div>
                            </div>
                            {{-- Title --}}
                            <div class="container flex justify-between items-center">
                                <label for="" class="text-xl font-medium m-2">Name*</label> 
                            </div>
                            <div class="mb-3">
                                <input value="{{old('name',$users->name)}}" name="name" placeholder="Enter the name"
                                    type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-400">{{$message}}</p>
                                @enderror
                            </div>

                             {{-- Text --}}
                             <div class="container flex justify-between items-center">
                                <label for="" class="text-xl font-medium m-2">Email</label> 
                            </div>
                            <div class="mb-3">
                                <textarea name="email" class="border-gray-300 shadow-sm w-1/2 rounded-lg"
                                placeholder="Enter the content">{{ old('email', $users->email) }}</textarea>
                            
                                @error('email')
                                <p class="text-red-400">{{$message}}</p>
                                @enderror
                            </div>
                             {{-- for roles --}}
                            <div class="grid grid-cols-4 mb-6">
                                @if($roles->isNotEmpty())
                                @foreach ($roles as $role )
                                <div class="mt-3">
                                    <input {{($hasRoles->contains($role->id)) ? 'checked' : ''}}

                                     type="checkbox" id="role-{{$role->id}}" class="rounded" name="role[]"
                                    value="{{$role->name}}">
                                    <label for="role-{{$role->id}}">{{$role->name}}</label>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <button class="bg-black text-gray-200 rounded-md px-4 py-2">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>