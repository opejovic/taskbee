@foreach($bundles as $bundle)
	{{ $bundle->name }}
	{{ $bundle->members_limit }}
	{{ $bundle->storage }}
	{{ $bundle->price }}
	{{ $bundle->additional_information }}
@endforeach