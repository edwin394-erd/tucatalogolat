<div>
  
<div class="p-4 md:p-5 bg-white md:m-5 rounded-lg md:w-1/2" >
        <h2 class="text-2xl font-bold text-gray-700 mb-4">{{__('messages.account')}}</h2>
        <hr class="border-gray-400 my-4">
  <form class="p-4 md:p-5" wire:submit.prevent="saveChanges" novalidate>
     <div>
        <label for="plan" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.plan') }}</label>
        <input type="text" wire:model.blur="plan" name="plan" id="plan" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="{{ __('messages.plan') }}" required="">
        @error('plan')
                <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="correo" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.your_email') }}</label>
        <input type="email" wire:model="correo" name="email" id="email" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="{{ __('messages.email_placeholder') }}" required="">
        @error('correo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    <br>

    <div>
        <label for="telephone" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.telephone') }}</label>
        <input type="tel" wire:model="telefono" name="telephone" id="telephone" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="{{ __('messages.telephone') }}" required="">
        @error('telefono')
                <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
<br>
 <div>
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.current_password_required')}}</label>
        <input type="password" wire:model.blur="password" name="password" id="password" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
        @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div><br>
<div class="flex gap-3">

        <div class="w-1/2">
                <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.new_password_optional') }} </label>
                <input type="password" wire:model.blur="new_password" name="new_password" id="new_password" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('new_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
        </div>
        <div class="w-1/2">
                <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.confirm_new_password') }}</label>
                <input type="password" wire:model.blur="new_password_confirmation" name="new_password_confirmation" id="new_password_confirmation" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                @error('new_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
        </div>
        
</div>
<br>

    <div class="flex justify-center p-0 m-0">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded ransition">{{ __('messages.save_changes') }}</button>
        </div>

  </form>



</div>

</div>
