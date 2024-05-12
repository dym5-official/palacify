import { h } from "preact";
import { useEffect, useState } from "preact/hooks";
import { Lod } from "./palacify";

import wirec from "wirec";

export default function App({ page }) {
    const [mod, setMod] = useState(null);

    useEffect(() => {
        const { unlink } = wirec.ons("render", (mod) => {
            setMod(mod);
        });

        return unlink;
    },[]);

    if ( ! mod || ! page(mod) ) {
        return (
            <div className="d5fc">
                <Lod size="28px" />
            </div>
        );
    }

    const Component = page(mod);

    return <Component />
}