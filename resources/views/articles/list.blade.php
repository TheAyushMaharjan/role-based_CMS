<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('LIST OF ARTICLES') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
    
            <!-- View Permissions Section -->
            <div class="view bg-white overflow-hidden flex justify-between mb-6 items-center shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("View Article") }}
                </div>
                @can('create articles')  
                <div class="create p-6">
                    <a class="bg-black text-gray-200 rounded-md px-4 py-2" href="{{route('articles.create')}}">Create</a>
                </div>
                @endcan

            </div>
    
            <!-- Table Section -->
            <div class="Table bg-white pt-10 shadow-sm sm:rounded-lg mb-6  px-4"> <!-- Added mb-6 -->
                <table id='myTable' class="w-full">
                    <thead>
                        <tr>
                            <th>Sno.</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Author</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($articles->isNotEmpty())
                        @foreach($articles as $article)
                        <tr>
                            <td>{{  $loop->iteration }}</td> <!-- Displays the row number -->
                            <td>{{ $article->title }}</td> <!-- Display the permission name -->
                            <td>{{ $article->text }}</td> <!-- Display the permission name -->
                            <td>{{ $article->author }}</td> <!-- Display the permission name -->

                            <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d M, Y') }}</td> <!-- Display the created_at date -->

                            <td>
                                <!-- Edit Button -->
                                @can('edit articles') 
                                <a class="bg-green-400 hover:bg-green-500 text-gray-700 rounded-md px-4 py-1 inline-block" href="{{route("articles.edit",$article->id)}}">Edit</a>
                                @endcan
                            
                                <!-- Delete Button inside Form -->
                                @can('delete articles') 
                                <form action="{{route("articles.delete",$article->id)}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-400 hover:bg-red-500 text-gray-700 rounded-md px-4 py-1 inline-block">
                                        Delete
                                    </button>
                                </form>
                                @endcan

                            </td>
                            
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="my-4 bg-white">
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>