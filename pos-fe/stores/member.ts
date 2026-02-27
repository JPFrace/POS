import { useLocalStorage } from "@vueuse/core";
import type { Member, Address } from "~/types/member";

export const useMemberStore = defineStore("member", () => {
    const member = useLocalStorage<Partial<Member>>("member", {});

    const setAddress = (address: Address) => {
        member.value.address = address;
    };

    return { setAddress, member };
});
