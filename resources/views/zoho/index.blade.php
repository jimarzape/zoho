<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Zoho Connection') }}
        </h2>
    </x-slot>
   <div class="py-12">
        <div  class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-xl font-semibold mb-4">Zoho configuration</h1>
                <form action="{{ route('zoho.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="client_id" class="block text-gray-700 text-sm font-bold mb-2">Cilent ID:</label>
                        <input type="text" name="client_id" id="client_id" placeholder="" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{$zoho ? $zoho->client_id : ''}}" required>
                        @if ($errors->has('client_id'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('client_id') }}</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="client_secret" class="block text-gray-700 text-sm font-bold mb-2">Client Secret:</label>
                        <input type="password" name="client_secret" id="client_secret" placeholder="********" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{$zoho ? $zoho->client_secret : ''}}" required>
                        @if ($errors->has('client_secret'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('client_secret') }}</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="redirect_uri" class="block text-gray-700 text-sm font-bold mb-2">Redirect URI:</label>
                        <input type="text" name="redirect_uri" id="redirect_uri" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" value="{{$zoho ? $zoho->redirect_uri : url('/')}}"  required>
                        @if ($errors->has('redirect_uri'))
                            <p class="text-red-500 text-xs italic">{{ $errors->first('redirect_uri') }}</p>
                        @endif
                    </div>

                    

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit and Connect
                        </button>
                      
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    

</x-app-layout>
