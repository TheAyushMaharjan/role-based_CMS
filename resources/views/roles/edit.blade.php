<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Role / Edit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{route('roles.update',$role->id)}}" method="post">
                        @csrf
                        <div>
                            <div class="container flex justify-between items-center">
                                <label for="" class="text-xl font-medium">Name</label>
                                <div class="create  ">
                                    <a class=" px-4 py-2 flex items-center" href="{{route('roles.index')}}"><i
                                            class="fa-solid fa-angle-left text-2xl p-3"></i> Back</a>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input value="{{old('name', $role->name)}}" name="name"
                                    type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                                @error('name')
                                <p class="text-red-400">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-4 mb-6">
                                
                                @if($permissions->isNotEmpty())
                                @foreach ($permissions as $permission )
                                <div class="mt-3">
                                    <input {{($hasPermissions->contains($permission->name)) ? 'checked' : ''}} type="checkbox" id="permission-{{$permission->id}}" class="rounded" name="permission[]"
                                    value="{{$permission->name}}">
                                    <label for="permission-{{$permission->id}}">{{$permission->name}}</label>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <button class="hover:bg-black bg-gray-800 text-gray-200 rounded-md px-4 py-2">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>