<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            All Category
            <b style="float: right;">Total Categories <span class="badge bg-primary">{{ count($categories) }}</span></b>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">SL No</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Deleted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach($categories as $category)
                        <tr>
                            <th scope="row">{{ $i++ }}</th>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ Carbon\Carbon::parse($category->created_at)->diffForHumans() }}</td>
                            <td>{{ Carbon\Carbon::parse($category->updated_at)->diffForHumans() }}</td>
                            <td>{{ Carbon\Carbon::parse($category->deleted_at)->diffForHumans() }}</td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>