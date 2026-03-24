<div class="flex items-center justify-between mb-8">

    <template x-for="(label,index) in steps()" :key="index">

        <div class="flex-1 flex items-center">

            <!-- STEP CIRCLE -->

            <div
                class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-semibold"
                :class="step > index
                    ? 'bg-red-600 text-white'
                    : step === index+1
                        ? 'bg-red-500 text-white'
                        : 'bg-zinc-700 text-gray-400'">

                <span x-text="index+1"></span>

            </div>

            <!-- STEP LABEL -->

            <span
                class="ml-2 text-sm"
                :class="step === index+1
                    ? 'text-white font-medium'
                    : 'text-gray-400'"

                x-text="label">

            </span>

            <!-- CONNECTOR LINE -->

            <div
                x-show="index < steps().length-1"
                class="flex-1 h-[2px] mx-4"
                :class="step > index+1
                    ? 'bg-red-600'
                    : 'bg-zinc-700'">
            </div>

        </div>

    </template>

</div>