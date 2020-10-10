@extends('layouts.app')
   
@section('content')
<h1>Command</h1>
<h3>Run below command for Sample data generate</h3>

<pre>
	<div class="hljs ini">
		Command: php artisan generate:data {NUMBER_OF_RECORD}.
		Command: php artisan create:files {NUMBER_OF_FILES}
	</div>
</pre>
@endsection